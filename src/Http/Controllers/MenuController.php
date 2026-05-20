<?php

namespace ME\Http\Controllers;

use ME\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ME\Models\Menu;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('authorization:me_menus.view')->only(['index', 'store', 'update', 'destroy']);
    }

    public function index(Request $request): View
    {
        $menus = Menu::query()
            ->when($request->name, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->url, function ($query, $url) {
                $query->where('url', 'like', "%{$url}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('order', 'desc')
            ->paginate(get_setting('pagination'))
            ->withQueryString();

        return view('me::menus.index', [
            'menus' => $menus,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Menu::create($validated);

        return redirect()->route('me.menus.index')->with('success', __('Menu created successfully.'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $menu->update($validated);

        return redirect()->route('me.menus.index')->with('success', __('Menu updated successfully.'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('me.menus.index')->with('success', __('Menu deleted successfully.'));
    }
}
