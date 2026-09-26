<?php

namespace ME\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Powers the topbar search box: searches every leaf item of the fully
 * assembled config('sidebar') (main app + every package's merged-in
 * sidebar, see AppServiceProvider::mergePackageSidebar()) by title, applying
 * the exact same 'permit' -> auth()->user()->can() gate sidebar.blade.php
 * itself uses so a result never links anywhere the current user couldn't
 * already see in the sidebar.
 */
class MenuSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if ($term === '') {
            return response()->json([]);
        }

        $items = $this->flatten(config('sidebar', []));
        $user = $request->user();

        $matches = [];
        foreach ($items as $item) {
            $title = $item['title'] ?? '';
            $meta = $item['meta'] ?? '';
            $translatedTitle = menu_trans($title);

            // Match the English title and the current-language title, so both "sales" and "বিক্রয়" find it
            $titleMatch = mb_stripos((string) $title, $term) !== false || mb_stripos($translatedTitle, $term) !== false;
            $metaMatch  = mb_stripos((string) $meta, $term) !== false;

            if (! $titleMatch && ! $metaMatch) {
                continue;
            }

            $item['title'] = $translatedTitle;
            $item['breadcrumb'] = collect(explode(' / ', $item['breadcrumb']))->filter()->map(fn ($part) => menu_trans($part))->implode(' / ');

            $permit = $item['permit'];
            if ($permit !== '' && (! $user || ! $user->can($permit))) {
                continue;
            }

            try {
                $item['route'] = route($item['route']);
            } catch (\Exception $e) {
                continue;
            }

            unset($item['permit']);
            $matches[] = $item;

            if (count($matches) >= 10) {
                break;
            }
        }

        return response()->json($matches);
    }

    private function flatten(array $groups): array
    {
        $items = [];

        $walk = function (array $nodes, array $trail) use (&$walk, &$items) {
            foreach ($nodes as $key => $node) {
                if ($key === 'group_title' || ! is_array($node)) {
                    continue;
                }

                $title = $node['title'] ?? null;

                if (isset($node['children']) && is_array($node['children'])) {
                    $walk($node['children'], $title ? [...$trail, $title] : $trail);
                    continue;
                }

                if ($title && ! empty($node['route'])) {
                    $items[] = [
                        'title'      => $title,
                        'route'      => $node['route'],
                        'icon'       => $node['icon'] ?? 'fa-solid fa-circle',
                        'breadcrumb' => implode(' / ', $trail),
                        'permit'     => $node['permit'] ?? '',
                        'meta'       => $node['meta'] ?? '',
                    ];
                }
            }
        };

        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }

            $groupTitle = $group['group_title'] ?? null;
            $walk([$group], $groupTitle ? [$groupTitle] : []);
        }

        return $items;
    }
}
