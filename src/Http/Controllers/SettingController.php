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
        $this->middleware('authorization:me_setting.configurations')->only(['editConfigurations', 'updateConfigurations']);
        $this->middleware('authorization:me_setting.settings')->only(['edit', 'update']);
    }

    public function editConfigurations()
    {
        // Get all settings
        $settings = [
            'pagination'             => (int) Setting::get('pagination', 10),
            'enable_translation'     => (bool) Setting::get('enable_translation', false),
            'enable_registration'    => (bool) Setting::get('enable_registration', false),
            'enable_forget_password' => (bool) Setting::get('enable_forget_password', false),
            'show_settings_link'     => (bool) Setting::get('show_settings_link', true),
            'app_logo'               => Setting::get('app_logo'),
            'app_ico'                => Setting::get('app_ico'),
        ];

        return view('me::settings.configurations', compact('settings'));
    }

    public function updateConfigurations(Request $request)
    {
        $request->validate([
            'pagination'  => 'required|integer|min:1',
            'app_logo'    => 'nullable|image|max:4096',
            'app_ico'     => 'nullable|file|mimes:svg,ico,png,jpg|max:1024',
        ]);

        $logKeys = ['pagination', 'enable_translation', 'enable_registration', 'enable_forget_password', 'show_settings_link', 'app_logo', 'app_ico'];
        $before = Setting::snapshot($logKeys);

        // Store values properly
        Setting::set('pagination', (int) $request->pagination);
        Setting::set('enable_translation', $request->has('enable_translation'));
        Setting::set('enable_registration', $request->has('enable_registration'));
        Setting::set('enable_forget_password', $request->has('enable_forget_password'));
        Setting::set('show_settings_link', $request->has('show_settings_link'));

        // Images live in me_media (Media Library); get_image('app_logo') reads them
        foreach (['app_logo', 'app_ico'] as $imgField) {
            if ($request->hasFile($imgField)) {
                Setting::setImage($imgField, $request->file($imgField));
            }
        }

        me_change_log('Configurations updated', 'settings.configurations')
            ->labels([
                'pagination' => __('me::me.Results per page'),
                'enable_translation' => __('me::me.Enable Translation'),
                'enable_registration' => __('me::me.Enable Registration'),
                'enable_forget_password' => __('me::me.Enable Forget Password'),
                'show_settings_link' => __('me::me.Show Settings Link in Profile Menu'),
                'app_logo' => __('me::me.App Logo'),
                'app_ico' => 'Favicon',
            ])
            ->record($before, Setting::snapshot($logKeys));

        return redirect()->route('configurations.edit')
            ->with('success', __('me::me.Configurations updated successfully'));
    }

    public function edit()
    {
        // Get all settings
        $settings = [
            'app_name' => Setting::get('app_name', 'My Shop'),
            'app_address' => Setting::get('app_address', ''),
            'app_email' => Setting::get('app_email', ''),
            'app_phone' => Setting::get('app_phone', ''),
            'app_logo' => Setting::get('app_logo'),
            'low_stock_threshold' => Setting::get('low_stock_threshold', 5),
            'sms_permit' => Setting::get('sms_permit'),
        ];

        return view('me::settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_address' => 'nullable|string',
            'app_email' => 'nullable|email|max:255',
            'app_phone' => 'nullable|string|max:50',
            'app_logo' => 'nullable|image|max:2048',
            'low_stock_threshold' => 'required|integer|min:1',
            'sms_notifications' => 'nullable|array',
            'sms_notifications.*' => 'boolean',
        ]);

        $logKeys = ['app_name', 'app_address', 'app_email', 'app_phone', 'low_stock_threshold', 'sms_notifications', 'app_logo'];
        $before = Setting::snapshot($logKeys);

        // Update text settings
        Setting::set('app_name', $request->app_name);
        Setting::set('app_address', $request->app_address);
        Setting::set('app_email', $request->app_email);
        Setting::set('app_phone', $request->app_phone);
        Setting::set('low_stock_threshold', $request->low_stock_threshold);
        Setting::set('sms_notifications', $request->sms_notifications);

        if ($request->hasFile('app_logo')) {
            Setting::setImage('app_logo', $request->file('app_logo'));
        }

        me_change_log('Settings updated', 'settings.general')->record($before, Setting::snapshot($logKeys));

        return redirect()->route('settings.edit')
            ->with('success', __('me::me.Settings updated successfully'));
    }

}
