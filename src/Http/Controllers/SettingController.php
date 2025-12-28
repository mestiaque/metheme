<?php

namespace ME\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ME\Models\Setting;
use Illuminate\Support\Facades\Storage;
use ME\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_setting.configurations')
            ->only(['editConfigurations', 'updateConfigurations']);
    }

    public function editConfigurations()
    {
        // Get all settings
        $settings = [
            'pagination'         => (int) Setting::get('pagination', 10),
            'enable_translation' => (bool) Setting::get('enable_translation', false),
            'root_url'           => Setting::get('root_url', url('/')),
            'profile_url'        => Setting::get('profile_url', url('/profile')),
            'setting_url'        => Setting::get('setting_url', url('/settings')),
            'logout_url'         => Setting::get('logout_url', url('/logout')),
            'login_url'          => Setting::get('login_url', url('/login')),
            'app_logo'           => Setting::get('app_logo'),
            'app_ico'            => Setting::get('app_ico'),
        ];

        return view('me::settings.configurations', compact('settings'));
    }

    public function updateConfigurations(Request $request)
    {
        $request->validate([
            'pagination'  => 'required|integer|min:1',
            'root_url'    => 'nullable|url',
            'profile_url' => 'nullable|url',
            'setting_url' => 'nullable|url',
            'logout_url'  => 'nullable|url',
            'login_url'   => 'nullable|url',
            'app_logo'    => 'nullable|image|max:4096',
            'app_ico'     => 'nullable|image|max:4096',
        ]);

        // Store values properly
        Setting::set('pagination', (int) $request->pagination);
        Setting::set('enable_translation', $request->has('enable_translation'));
        Setting::set('root_url', $request->root_url);
        Setting::set('profile_url', $request->profile_url);
        Setting::set('setting_url', $request->setting_url);
        Setting::set('logout_url', $request->logout_url);
        Setting::set('login_url', $request->login_url);

        foreach (['app_logo', 'app_ico'] as $imgField) {
            if ($request->hasFile($imgField)) {
                $image = $request->file($imgField);
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $imagePath = storage_path("app/public/images/{$imgField}");

                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }

                $image->move($imagePath, $imageName);
                Setting::set($imgField, $imageName);
            }
        }

        return redirect()->route('me.configurations.edit')
            ->with('success', 'Configurations updated successfully.');
    }
}
