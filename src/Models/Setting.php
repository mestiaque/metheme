<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use ME\Traits\HasMedia;

class Setting extends Model
{
    use HasFactory, HasMedia;

    /**
     * An image setting (app_logo, app_ico, ecom_store_logo …) keeps its file in me_media,
     * attached to the setting row in the "image" collection; the row's value holds the media uuid.
     */
    protected function mediaCollections(): array
    {
        return [
            'image' => ['single' => true, 'mimes' => 'jpg,jpeg,png,webp,gif,svg,ico', 'max_kb' => 4096, 'conversions' => ['thumb' => 200]],
        ];
    }

    /**
     * Save an image setting (the previous image goes to the media trash).
     */
    public static function setImage(string $key, UploadedFile|string $file): Media
    {
        $setting = static::firstOrCreate(['key' => $key]);
        $media = $setting->addMedia($file, 'image');
        $setting->forceFill(['value' => $media->uuid])->save();

        return $media;
    }

    public static function image(string $key): ?Media
    {
        return static::where('key', $key)->first()?->firstMedia('image');
    }

    /**
     * URL of an image setting, or null. Prefer the get_image() helper in views.
     */
    public static function imageUrl(string $key, ?string $conversion = null): ?string
    {
        return static::image($key)?->url($conversion);
    }

    public static function removeImage(string $key): void
    {
        if ($setting = static::where('key', $key)->first()) {
            $setting->clearMedia('image');
            $setting->forceFill(['value' => null])->save();
        }
    }

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     *
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // "sms_permit" is the decoded sms_notifications checkboxes (create/edit/payment/reminder)
        if ($key === 'sms_permit') {
            $value = static::get('sms_notifications');

            return is_array($value) ? $value : (json_decode($value ?? '', true) ?: ($default ?? []));
        }

        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     *
     * @param  mixed  $value
     * @return \App\Models\Setting
     */
    public static function set(string $key, $value)
    {
        $setting = static::firstOrCreate(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->save();

        return $setting;
    }

    /**
     * Current values of the given keys (missing keys = null). Used for the data change log:
     *   $before = Setting::snapshot($keys); ...save...; me_change_log(...)->record($before, Setting::snapshot($keys));
     */
    public static function snapshot(array $keys): array
    {
        $values = static::whereIn('key', $keys)->pluck('value', 'key')->all();

        return collect($keys)->mapWithKeys(fn ($key) => [$key => $values[$key] ?? null])->all();
    }
}
