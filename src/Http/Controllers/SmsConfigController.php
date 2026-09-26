<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use ME\Models\Setting;
use ME\Services\SmsService;

class SmsConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_setting.sms');
    }

    public function edit()
    {
        $settings = [
            'enable_sms'    => (bool) Setting::get('enable_sms', false),
            'sms_api_url'   => Setting::get('sms_api_url', config('services.sms_api_url')),
            'sms_sender_id' => Setting::get('sms_sender_id', config('services.sms_sender_id')),
            'sms_balance_url' => Setting::get('sms_balance_url', config('services.sms_balance_url')),
            'has_api_key'   => filled(Setting::get('sms_api_key')),
        ];

        return view('me::settings.sms-config', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sms_api_url'   => 'required_with:enable_sms|nullable|url|max:255',
            'sms_api_key'   => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:50',
            'sms_balance_url' => 'nullable|url|max:255',
        ]);

        Setting::set('enable_sms', $request->has('enable_sms'));
        Setting::set('sms_api_url', $request->sms_api_url);
        Setting::set('sms_sender_id', $request->sms_sender_id);
        Setting::set('sms_balance_url', $request->sms_balance_url);

        // Blank API key field keeps the saved one; it is stored encrypted
        if ($request->filled('sms_api_key')) {
            Setting::set('sms_api_key', Crypt::encryptString($request->sms_api_key));
        }

        return redirect()->route('me.sms-config.edit')
            ->with('success', __('me::me.sms_settings_saved'));
    }

    /**
     * Send a test SMS through the saved gateway (applied at boot by MEServiceProvider).
     */
    public function test(Request $request)
    {
        $request->validate([
            'test_phone'   => 'required|string|max:20',
            'test_message' => 'required|string|max:500',
        ]);

        $result = SmsService::send($request->test_phone, $request->test_message);

        if (!$result['success']) {
            return back()->withInput()->with('error', __('me::me.test_sms_failed') . ' ' . Str::squish($result['error'] ?? json_encode($result['response'])));
        }

        return back()->withInput()->with('success', __('me::me.test_sms_sent', ['phone' => $request->test_phone]));
    }
}
