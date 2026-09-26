@extends('me::master')

@section('title', trans('me::me.mail_log'))

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold">@lang('me::me.sent_this_month')</div>
            <div class="h4 fw-bold mb-0 text-success">{{ toBanglaNumber($sentThisMonth) }}</div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold">@lang('me::me.failed_this_month')</div>
            <div class="h4 fw-bold mb-0 {{ $failedThisMonth ? 'text-danger' : '' }}">{{ toBanglaNumber($failedThisMonth) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card glass-card h-100 p-3 small text-muted d-flex flex-row align-items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span>
                @lang('me::me.mail_log_hint')
                @if(auth()->user()->hasPermission('me_setting.mail'))
                    <a href="{{ route('me.mail-config.edit') }}">@lang('me::me.mail_configuration')</a>
                @endif
            </span>
        </div>
    </div>
</div>

<div class="card glass-card w-100">
    <form method="GET" action="{{ route('me.mail-log.index') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="@lang('me::me.recipient_or_subject')" value="{{ request('search') }}">
            </div>
            <div class="col-md">
                <select name="status" class="form-select form-select-sm">
                    <option value="">@lang('me::me.all_statuses')</option>
                    @foreach (['sent', 'sending'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>@lang('me::me.mail_status_' . $status)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="@lang('me::me.from_date')">
            </div>
            <div class="col-md">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="@lang('me::me.to_date')">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-sm btn-encodex-search rounded"><i class="fas fa-search"></i> @lang('me::me.Search')</button>
                <a href="{{ route('me.mail-log.index') }}" class="btn btn-sm btn-encodex-clear rounded"><i class="fas fa-eraser"></i> @lang('me::me.Reset')</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped table-encodex">
            <thead>
                <tr>
                    <th>@lang('me::me.recipient')</th>
                    <th>@lang('me::me.subject')</th>
                    <th>@lang('me::me.mail_driver')</th>
                    <th>@lang('me::me.Status')</th>
                    <th>@lang('me::me.time')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-break">
                            {{ $log->to }}
                            @if($log->cc)
                                <div class="small text-muted">CC: {{ $log->cc }}</div>
                            @endif
                        </td>
                        <td>{{ $log->subject ?? '—' }}</td>
                        <td class="text-nowrap">{{ $log->mailer }}</td>
                        <td>
                            <span class="badge {{ $log->status === 'sent' ? 'bg-success' : 'bg-danger' }}">
                                <i class="fas fa-{{ $log->status === 'sent' ? 'check' : 'times' }}"></i>
                                @lang('me::me.mail_status_' . $log->status)
                            </span>
                        </td>
                        <td class="text-nowrap">{{ formatDateTime($log->created_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">@lang('me::me.no_mail_logs_found')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
@endsection
