<?php

namespace ME\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ME\Models\UserActivity;
use Throwable;

/**
 * Records the before/after data of one action as ONE readable entry in the activity log,
 * including related models (e.g. an order and its items).
 *
 *   $log = me_change_log('Order #12 updated', 'order.update')
 *       ->watch($order, ['items'])                          // snapshot before (model + relations)
 *       ->labels(['status' => 'Status', 'items.qty' => 'Qty'])
 *       ->itemName('items', fn ($item) => $item['product_name']);
 *   // ... update the order and its items ...
 *   $log->save();                                           // snapshot after, diff, write
 *
 *   me_change_log('User updated')->watch($user, ['roles'])->run(fn () => ...);
 *   me_change_log('Order created')->with(['items'])->create(fn () => Order::create($data));
 *   me_change_log('Order deleted')->watch($order, ['items'])->delete(fn () => $order->delete());
 *   me_change_log('SMS settings changed', 'settings.sms')->record($old, $new);
 *
 * Each change row: [section, item, field, label, old, new, type: changed|added|removed|hidden].
 */
class DataChangeLogger
{
    protected ?string $title;

    protected ?string $slug;

    protected ?string $action = null;

    protected ?Model $subject = null;

    protected array $relations = [];

    protected array $labels = [];

    protected array $itemNames = [];

    protected ?array $before = null;

    protected bool $saved = false;

    public function __construct(?string $title = null, ?string $slug = null)
    {
        $this->title = $title;
        $this->slug = $slug;
    }

    /* ------------------------------------------------------------------ fluent setup */

    /**
     * Start watching a record (and the given relations). Takes the "before" snapshot now.
     */
    public function watch(Model $model, array $relations = []): static
    {
        $this->subject = $model;
        $this->relations = $relations;
        $this->before = $this->snapshot($model, $relations, fresh: true);

        return $this;
    }

    /**
     * Relations to include when the record is created inside create().
     */
    public function with(array $relations): static
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Readable names: 'status' => 'Status', 'items' => 'Items', 'items.qty' => 'Qty'.
     */
    public function labels(array $labels): static
    {
        $this->labels = array_merge($this->labels, $labels);

        return $this;
    }

    /**
     * How to name one related item in the log: an attribute name or fn (array $attributes) => string.
     */
    public function itemName(string $relation, callable|string $name): static
    {
        $this->itemNames[$relation] = $name;

        return $this;
    }

    /**
     * Action word used in the default title/slug (update, create, delete, approve, ...).
     */
    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /* ------------------------------------------------------------------ run + save */

    /**
     * Run the change, then log it. Returns whatever the callback returns.
     */
    public function run(callable $callback): mixed
    {
        $result = $callback();
        $this->save();

        return $result;
    }

    /**
     * Create a record (callback returns the new model) and log all its data as "added".
     */
    public function create(callable $callback): mixed
    {
        $this->action ??= 'create';
        $this->before = null;

        $result = $callback();

        if ($result instanceof Model) {
            $this->subject = $result;
        }

        $this->write(null, $this->subject ? $this->snapshot($this->subject, $this->relations, fresh: true) : null);

        return $result;
    }

    /**
     * Delete the watched record and log the data it had.
     */
    public function delete(callable $callback): mixed
    {
        $this->action ??= 'delete';

        $result = $callback();
        $this->write($this->before, null);

        return $result;
    }

    /**
     * Take the "after" snapshot of the watched record and log the differences.
     */
    public function save(): ?UserActivity
    {
        $this->action ??= 'update';

        if (! $this->subject) {
            return null;
        }

        $after = $this->subject->exists ? $this->snapshot($this->subject, $this->relations, fresh: true) : null;

        return $this->write($this->before, $after);
    }

    /**
     * Log plain arrays (no model), e.g. settings: record($oldSettings, $newSettings).
     */
    public function record(array $old, array $new): ?UserActivity
    {
        // Empty "old" = something was created, empty "new" = something was deleted
        $this->action ??= $old === [] ? 'create' : ($new === [] ? 'delete' : 'update');

        return $this->write(
            $old === [] ? null : ['attributes' => $old, 'relations' => []],
            $new === [] ? null : ['attributes' => $new, 'relations' => []]
        );
    }

    /**
     * Attach the log to a record (for history links) when using record() without watch().
     */
    public function subject(Model $model): static
    {
        $this->subject = $model;

        return $this;
    }

    /* ------------------------------------------------------------------ internals */

    protected function write(?array $before, ?array $after): ?UserActivity
    {
        if ($this->saved || ! config('me_settings.data_change_log.enabled', true)) {
            return null;
        }

        try {
            $changes = $this->diff($before, $after);

            if ($changes === []) {
                return null; // nothing changed
            }

            $this->saved = true;
            $action = $this->action ?? 'update';
            $subjectName = $this->subject ? class_basename($this->subject) : null;

            $verb = ['create' => 'created', 'update' => 'updated', 'delete' => 'deleted'][$action] ?? Str::lower(Str::headline($action));
            $title = $this->title
                ?? trim(($subjectName ? $subjectName.($this->subject?->getKey() ? ' #'.$this->subject->getKey() : '').' ' : 'Data ').$verb);
            $slug = $this->slug
                ?? ($subjectName ? Str::snake($subjectName).'.' : 'data.').$action;

            return (new ActivityLoggerService(request()))->logChange(
                auth()->id(),
                $slug,
                $title,
                $changes,
                $this->subject ? $this->subject->getMorphClass() : null,
                $this->subject?->getKey()
            );
        } catch (Throwable $e) {
            report($e); // logging must never break the action itself

            return null;
        }
    }

    /**
     * ['attributes' => [...], 'relations' => [name => ['many' => bool, 'items' => [id => attrs]]]]
     */
    protected function snapshot(Model $model, array $relations, bool $fresh = false): array
    {
        $source = $fresh && $model->exists ? ($model->fresh($relations) ?? $model) : $model->loadMissing($relations);

        $data = ['attributes' => $this->clean($source->getAttributes()), 'relations' => []];

        foreach ($relations as $relation) {
            $value = $source->getRelation($relation);

            if ($value instanceof EloquentCollection || is_iterable($value) && ! $value instanceof Model) {
                $items = [];
                foreach ($value as $item) {
                    $items[(string) $item->getKey()] = $this->clean($item->getAttributes());
                }
                $data['relations'][$relation] = ['many' => true, 'items' => $items];
            } else {
                $data['relations'][$relation] = [
                    'many' => false,
                    'items' => $value instanceof Model ? [(string) $value->getKey() => $this->clean($value->getAttributes())] : [],
                ];
            }
        }

        return $data;
    }

    protected function diff(?array $before, ?array $after): array
    {
        $rows = [];

        // main record fields
        $old = $before['attributes'] ?? [];
        $new = $after['attributes'] ?? [];
        $type = $before === null ? 'added' : ($after === null ? 'removed' : 'changed');

        foreach (array_unique(array_merge(array_keys($old), array_keys($new))) as $field) {
            $rows = array_merge($rows, $this->fieldRows(null, null, $field, $old[$field] ?? null, $new[$field] ?? null, $type));
        }

        // relations
        $relations = array_unique(array_merge(array_keys($before['relations'] ?? []), array_keys($after['relations'] ?? [])));

        foreach ($relations as $relation) {
            $oldItems = $before['relations'][$relation]['items'] ?? [];
            $newItems = $after['relations'][$relation]['items'] ?? [];

            foreach ($newItems as $id => $attributes) {
                if (! array_key_exists($id, $oldItems)) {
                    $name = $this->nameOf($relation, $attributes, $id);
                    $rows[] = $this->row($relation, $name, null, null, null, $this->summary($relation, $attributes, $name), 'added');
                }
            }

            foreach ($oldItems as $id => $attributes) {
                if (! array_key_exists($id, $newItems)) {
                    $name = $this->nameOf($relation, $attributes, $id);
                    $rows[] = $this->row($relation, $name, null, null, $this->summary($relation, $attributes, $name), null, 'removed');

                    continue;
                }

                $name = $this->nameOf($relation, $newItems[$id], $id);
                foreach (array_unique(array_merge(array_keys($attributes), array_keys($newItems[$id]))) as $field) {
                    $rows = array_merge($rows, $this->fieldRows($relation, $name, $field, $attributes[$field] ?? null, $newItems[$id][$field] ?? null, 'changed'));
                }
            }
        }

        return array_values($rows);
    }

    /**
     * Rows for one field. Lists of plain values (e.g. permission keys) become added/removed rows.
     */
    protected function fieldRows(?string $section, ?string $item, string $field, $old, $new, string $type): array
    {
        if ($this->ignored($field)) {
            return [];
        }

        $old = $this->decode($old);
        $new = $this->decode($new);

        if ($this->hidden($field)) {
            if ($type === 'changed' && $old === $new) {
                return [];
            }

            return [$this->row($section, $item, $field, $this->label($section, $field), null, null, 'hidden')];
        }

        if (is_array($old) && is_array($new) && array_is_list($old) && array_is_list($new)) {
            $rows = [];
            foreach (array_diff($new, $old) as $value) {
                $rows[] = $this->row($section, $item, $field, $this->label($section, $field), null, $this->format($value), 'added');
            }
            foreach (array_diff($old, $new) as $value) {
                $rows[] = $this->row($section, $item, $field, $this->label($section, $field), $this->format($value), null, 'removed');
            }

            return $rows;
        }

        if ($type === 'changed' && $this->same($old, $new)) {
            return [];
        }

        if ($type === 'added' && ($new === null || $new === '' || $new === [])) {
            return [];
        }

        if ($type === 'removed' && ($old === null || $old === '' || $old === [])) {
            return [];
        }

        return [$this->row($section, $item, $field, $this->label($section, $field), $this->format($old), $this->format($new), $type === 'changed' ? 'changed' : $type)];
    }

    protected function row(?string $section, ?string $item, ?string $field, ?string $label, $old, $new, string $type): array
    {
        return [
            'section' => $section,
            'section_label' => $section ? ($this->labels[$section] ?? Str::headline($section)) : null,
            'item' => $item,
            'field' => $field,
            'label' => $label,
            'old' => $old,
            'new' => $new,
            'type' => $type,
        ];
    }

    /**
     * One-line summary of a related item for added/removed rows: "Qty: 2, Price: 10".
     */
    protected function summary(string $relation, array $attributes, ?string $name = null): string
    {
        $parts = [];
        foreach ($attributes as $field => $value) {
            if ($this->ignored($field) || $this->hidden($field) || $field === 'id' || str_ends_with($field, '_id')
                || $value === null || $value === '' || (string) $value === (string) $name) {
                continue;
            }
            $parts[] = $this->label($relation, $field).': '.$this->format($this->decode($value));
        }

        return $this->truncate(implode(', ', $parts));
    }

    protected function nameOf(string $relation, array $attributes, string $id): string
    {
        $rule = $this->itemNames[$relation] ?? null;

        if (is_callable($rule)) {
            return (string) $rule($attributes);
        }

        if (is_string($rule) && isset($attributes[$rule])) {
            return (string) $attributes[$rule];
        }

        return (string) ($attributes['name'] ?? $attributes['title'] ?? ('#'.$id));
    }

    protected function label(?string $section, string $field): string
    {
        return $this->labels[$section ? "{$section}.{$field}" : $field]
            ?? $this->labels[$field]
            ?? Str::headline($field);
    }

    protected function clean(array $attributes): array
    {
        return $attributes; // hidden/ignored fields are handled per field in fieldRows()
    }

    protected function ignored(string $field): bool
    {
        return in_array($field, (array) config('me_settings.data_change_log.ignore_fields', []), true);
    }

    protected function hidden(string $field): bool
    {
        foreach ((array) config('me_settings.data_change_log.hidden_fields', []) as $hidden) {
            if ($field === $hidden || str_ends_with($field, '_'.$hidden)) {
                return true;
            }
        }

        return false;
    }

    protected function decode($value)
    {
        if (is_string($value) && $value !== '' && in_array($value[0], ['[', '{'], true)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }

    protected function same($old, $new): bool
    {
        if (is_numeric($old) && is_numeric($new)) {
            return (float) $old === (float) $new;
        }

        return $this->format($old) === $this->format($new);
    }

    protected function format($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $this->truncate((string) $value);
    }

    protected function truncate(string $value): string
    {
        $max = (int) config('me_settings.data_change_log.max_value_length', 2000);

        return mb_strlen($value) > $max ? mb_substr($value, 0, $max).'…' : $value;
    }
}
