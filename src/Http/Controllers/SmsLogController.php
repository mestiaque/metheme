<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use ME\Models\SmsAccount;
use ME\Models\SmsLog;
use ME\Services\SmsService;

class SmsLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_sms.view')->only('index');
        $this->middleware('authorization:me_sms.recharge')->only('recharge');
    }

    public function index(Request $request)
    {
        $logs = SmsLog::query()
            ->when($request->phone, fn ($q, $phone) => $q->where('to', 'like', "%{$phone}%"))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->date_from, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->date_to, fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest('id')
            ->paginate(get_setting('pagination', 10))
            ->withQueryString();

        $account = SmsAccount::current();
        $monthStats = SmsLog::where('created_at', '>=', now()->startOfMonth())
            ->selectRaw("SUM(status = 'success') as sent, SUM(status <> 'success') as failed")
            ->first();

        return view('me::sms.log', [
            'logs'           => $logs,
            'account'        => $account,
            'sentThisMonth'  => (int) $monthStats->sent,
            'failedThisMonth' => (int) $monthStats->failed,
            'gateway'        => SmsService::gatewayBalance($request->boolean('refresh_balance')),
        ]);
    }

    public function recharge(Request $request)
    {
        $request->validate([
            'recharge' => 'required|numeric|min:0',
            'rate'     => 'nullable|numeric|min:0.01',
        ]);

        $account = SmsAccount::first() ?? new SmsAccount(['sms_used' => 0, 'admin_recharge_amount' => 0, 'balance' => 0, 'sms_rate' => 0]);
        $account->admin_recharge_amount += $request->recharge;
        $account->balance += $request->recharge;
        $account->sms_rate = $request->rate ?? $account->sms_rate;
        $account->save();

        return redirect()->route('me.sms-log.index')->with('success', __('me::me.sms_account_updated'));
    }
}
