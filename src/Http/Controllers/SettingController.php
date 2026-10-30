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
            'enable_page_loader'     => (bool) Setting::get('enable_page_loader', true),
            'page_loader'            => Setting::get('page_loader', config('me_settings.default_page_loader', 'premium-svg-draw')),
            'app_logo'               => Setting::get('app_logo'),
            'app_ico'                => Setting::get('app_ico'),
            // Logo in the top header and in the page title bar: on/off + optional image
            // (no image = the built-in SVG logo)
            'show_header_logo'       => (bool) Setting::get('show_header_logo', true),
            'show_title_logo'        => (bool) Setting::get('show_title_logo', true),
            'show_footer_authorization' => (bool) Setting::get('show_footer_authorization', true),
            'header_logo'            => Setting::get('header_logo'),
            'title_logo'             => Setting::get('title_logo'),
        ];

        return view('me::settings.configurations', compact('settings'));
    }

    public function updateConfigurations(Request $request)
    {
        $request->validate([
            'pagination'  => 'required|integer|min:1',
            'page_loader' => ['nullable', 'in:' . implode(',', array_keys(config('me_settings.page_loaders', [])))],
            'app_logo'    => 'nullable|image|max:4096',
            'app_ico'     => 'nullable|file|mimes:svg,ico,png,jpg|max:1024',
            'header_logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:4096',
            'title_logo'  => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:4096',
        ]);

        $logKeys = ['pagination', 'enable_translation', 'enable_registration', 'enable_forget_password', 'show_settings_link', 'enable_page_loader', 'page_loader', 'app_logo', 'app_ico',
            'show_header_logo', 'show_title_logo', 'show_footer_authorization', 'header_logo', 'title_logo'];
        $before = Setting::snapshot($logKeys);

        // Store values properly
        Setting::set('pagination', (int) $request->pagination);
        Setting::set('enable_translation', $request->has('enable_translation'));
        Setting::set('enable_registration', $request->has('enable_registration'));
        Setting::set('enable_forget_password', $request->has('enable_forget_password'));
        Setting::set('show_settings_link', $request->has('show_settings_link'));
        Setting::set('enable_page_loader', $request->has('enable_page_loader'));
        if ($request->filled('page_loader')) {
            Setting::set('page_loader', $request->page_loader);
        }
        Setting::set('show_header_logo', $request->has('show_header_logo'));
        Setting::set('show_title_logo', $request->has('show_title_logo'));
        Setting::set('show_footer_authorization', $request->has('show_footer_authorization'));

        // Images live in me_media (Media Library); get_image('app_logo') reads them
        foreach (['app_logo', 'app_ico', 'header_logo', 'title_logo'] as $imgField) {
            if ($request->hasFile($imgField)) {
                Setting::setImage($imgField, $request->file($imgField));
            }
        }

        // "Remove" goes back to the built-in SVG logo (a new upload in the same save wins)
        foreach (['header_logo', 'title_logo'] as $imgField) {
            if ($request->has("remove_{$imgField}") && !$request->hasFile($imgField)) {
                Setting::removeImage($imgField);
            }
        }

        me_change_log('Configurations updated', 'settings.configurations')
            ->labels([
                'pagination' => __('me::me.Results per page'),
                'enable_translation' => __('me::me.Enable Translation'),
                'enable_registration' => __('me::me.Enable Registration'),
                'enable_forget_password' => __('me::me.Enable Forget Password'),
                'show_settings_link' => __('me::me.Show Settings Link in Profile Menu'),
                'enable_page_loader' => __('me::me.enable_page_loader'),
                'page_loader' => __('me::me.page_loader'),
                'app_logo' => __('me::me.App Logo'),
                'app_ico' => 'Favicon',
                'show_header_logo' => __('me::me.show_header_logo'),
                'show_title_logo' => __('me::me.show_title_logo'),
                'show_footer_authorization' => __('me::me.show_footer_authorization'),
                'header_logo' => __('me::me.header_logo'),
                'title_logo' => __('me::me.title_logo'),
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
