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

        // Store values properly
        Setting::set('pagination', (int) $request->pagination);
        Setting::set('enable_translation', $request->has('enable_translation'));
        Setting::set('enable_registration', $request->has('enable_registration'));
        Setting::set('enable_forget_password', $request->has('enable_forget_password'));

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

        return redirect()->route('configurations.edit')
            ->with('success', 'Configurations updated successfully.');
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

        // Update text settings
        Setting::set('app_name', $request->app_name);
        Setting::set('app_address', $request->app_address);
        Setting::set('app_email', $request->app_email);
        Setting::set('app_phone', $request->app_phone);
        Setting::set('low_stock_threshold', $request->low_stock_threshold);
        Setting::set('sms_notifications', $request->sms_notifications);

        if ($request->hasFile('app_logo')) {
            $image = $request->file('app_logo');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = storage_path('app/public/images/app_logo');

            // Ensure the directory exists
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $image->move($imagePath, $imageName);
            // $data['app_logo'] = $imageName;
            Setting::set('app_logo', $imageName);
        }

        return redirect()->route(me_prefix().'.settings.edit')
            ->with('success', __('Settings updated successfully'));
    }

}
