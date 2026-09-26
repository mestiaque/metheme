<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use ME\Models\MailLog;

class MailLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_mail.view');
    }

    public function index(Request $request)
    {
        $logs = MailLog::query()
            ->when($request->search, fn ($q, $search) => $q->where(fn ($q) => $q->where('to', 'like', "%{$search}%")->orWhere('subject', 'like', "%{$search}%")))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->date_from, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->date_to, fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest('id')
            ->paginate(get_setting('pagination', 10))
            ->withQueryString();

        $monthStats = MailLog::where('created_at', '>=', now()->startOfMonth())
            ->selectRaw("SUM(status = 'sent') as sent, SUM(status <> 'sent') as failed")
            ->first();

        return view('me::mail.log', [
            'logs'            => $logs,
            'sentThisMonth'   => (int) $monthStats->sent,
            'failedThisMonth' => (int) $monthStats->failed,
        ]);
    }
}
