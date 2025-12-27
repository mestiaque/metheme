<?php

namespace Encodex\Metheme\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Encodex\Metheme\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Encodex\Metheme\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:encodex_setting.configurations')
            ->only(['editConfigurations', 'updateConfigurations']);
    }

    public function editConfigurations()
    {
        // Get all settings
        $settings = [
            'pagination' => (int) Setting::get('pagination', 10),
            'enable_translation' => (bool) Setting::get('enable_translation', false),
            'root_url' => Setting::get('root_url', url('/')),
            'profile_url' => Setting::get('profile_url', url('/profile')),
            'setting_url' => Setting::get('setting_url', url('/settings')),
            'logout_url' => Setting::get('logout_url', url('/logout')),
            'login_url' => Setting::get('login_url', url('/login')),
        ];

        return view('me::settings.configurations', compact('settings'));
    }

    public function updateConfigurations(Request $request)
    {
        $request->validate([
            'pagination' => 'required|integer|min:1',
            'root_url' => 'nullable|url',
            'profile_url' => 'nullable|url',
            'setting_url' => 'nullable|url',
            'logout_url' => 'nullable|url',
            'login_url' => 'nullable|url',
        ]);

        // Store values properly
        Setting::set('pagination', (int) $request->pagination);
        Setting::set('enable_translation', $request->has('enable_translation'));
        Setting::set('root_url', $request->root_url);
        Setting::set('profile_url', $request->profile_url);
        Setting::set('setting_url', $request->setting_url);
        Setting::set('logout_url', $request->logout_url);
        Setting::set('login_url', $request->login_url);

        return redirect()->route('encodex.configurations.edit')
            ->with('success', 'Configurations updated successfully.');
    }
}
