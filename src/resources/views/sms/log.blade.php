@extends('me::master')

@section('title', trans('me::me.sms_log_and_balance'))

@section('content')
{{-- Balance overview --}}
<div class="row g-3 mb-3">
    <div class="col-xl col-md-4 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold">@lang('me::me.local_balance')</div>
            <div class="h4 fw-bold mb-0 {{ $account->balance < $account->sms_rate ? 'text-danger' : 'text-success' }}">
                @lang('me::me.tk') {{ toBanglaNumber($account->balance, 2) }}
            </div>
            <div class="small text-muted">@lang('me::me.total_recharged'): @lang('me::me.tk') {{ toBanglaNumber($account->admin_recharge_amount, 2) }}</div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold">@lang('me::me.sms_remaining')</div>
            <div class="h4 fw-bold mb-0 text-primary">{{ toBanglaNumber($account->smsRemaining()) }}</div>
            <div class="small text-muted">@lang('me::me.sms_rate'): @lang('me::me.tk') {{ toBanglaNumber($account->sms_rate, 2) }}</div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold">@lang('me::me.sent_this_month')</div>
            <div class="h4 fw-bold mb-0">{{ toBanglaNumber($sentThisMonth) }}</div>
            <div class="small {{ $failedThisMonth ? 'text-danger' : 'text-muted' }}">
                @lang('me::me.failed'): {{ toBanglaNumber($failedThisMonth) }} · @lang('me::me.total_sent'): {{ toBanglaNumber($account->sms_used) }}
            </div>
        </div>
    </div>
    <div class="col-xl col-md-6 col-6">
        <div class="card glass-card h-100 p-3">
            <div class="small text-muted text-uppercase fw-semibold d-flex justify-content-between">
                <span>@lang('me::me.gateway_balance')</span>
                @if($gateway['checked_at'])
                    <a href="{{ request()->fullUrlWithQuery(['refresh_balance' => 1]) }}" title="@lang('me::me.refresh')"><i class="fas fa-sync-alt"></i></a>
                @endif
            </div>
            @if($gateway['balance'] !== null)
                <div class="h4 fw-bold mb-0">@lang('me::me.tk') {{ toBanglaNumber($gateway['balance'], 2) }}</div>
                <div class="small text-muted">@lang('me::me.checked_at') {{ $gateway['checked_at'] }}</div>
            @elseif($gateway['error'])
                <div class="small text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $gateway['error'] }}</div>
            @else
                <div class="small text-muted">
                    @lang('me::me.gateway_balance_not_set')
                    @if(auth()->user()->hasPermission('me_setting.sms'))
                        <a href="{{ route('me.sms-config.edit') }}">@lang('me::me.sms_configuration')</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Recharge --}}
@if(auth()->user()->hasPermission('me_sms.recharge'))
<div class="card glass-card mb-3">
    <form method="POST" action="{{ route('me.sms-log.recharge') }}" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-auto">
            <h6 class="mb-2 text-primary fw-semibold"><i class="fas fa-wallet me-1"></i> @lang('me::me.recharge_update_account')</h6>
        </div>
        <div class="col-md">
            <label for="recharge" class="form-label small mb-1">@lang('me::me.recharge_amount') <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="recharge" id="recharge" required
                   class="form-control form-control-sm @error('recharge') is-invalid @enderror" value="{{ old('recharge') }}">
            @error('recharge') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md">
            <label for="rate" class="form-label small mb-1">@lang('me::me.sms_rate_per_sms')</label>
            <input type="number" step="0.01" min="0.01" name="rate" id="rate" placeholder="{{ $account->sms_rate }}"
                   class="form-control form-control-sm @error('rate') is-invalid @enderror" value="{{ old('rate') }}">
            @error('rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-sm btn-encodex-save"><i class="fas fa-save me-1"></i> @lang('me::me.update')</button>
        </div>
    </form>
</div>
@endif

{{-- Log --}}
<div class="card glass-card w-100">
    <form method="GET" action="{{ route('me.sms-log.index') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md">
                <input type="text" name="phone" class="form-control form-control-sm" placeholder="@lang('me::me.mobile_number')" value="{{ request('phone') }}">
            </div>
            <div class="col-md">
                <select name="status" class="form-select form-select-sm">
                    <option value="">@lang('me::me.all_statuses')</option>
                    @foreach (['success', 'failed', 'error'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>@lang('me::me.sms_status_' . $status)</option>
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
                <a href="{{ route('me.sms-log.index') }}" class="btn btn-sm btn-encodex-clear rounded"><i class="fas fa-eraser"></i> @lang('me::me.Reset')</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped table-encodex">
            <thead>
                <tr>
                    <th>@lang('me::me.sent_to')</th>
                    <th>@lang('me::me.message')</th>
                    <th>@lang('me::me.Status')</th>
                    @if(auth()->user()->is_encodex())
                        <th>@lang('me::me.api_response')</th>
                    @endif
                    <th>@lang('me::me.time')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap">{{ toBanglaPhone($log->to) }}</td>
                        <td style="white-space: pre-line; min-width: 220px">{{ $log->message }}</td>
                        <td>
                            <span class="badge {{ $log->status === 'success' ? 'bg-success' : 'bg-danger' }}">
                                <i class="fas fa-{{ $log->status === 'success' ? 'check' : 'times' }}"></i>
                                @lang('me::me.sms_status_' . $log->status)
                            </span>
                        </td>
                        @if(auth()->user()->is_encodex())
                            <td class="small text-muted text-break" style="max-width: 280px">{{ \Illuminate\Support\Str::limit($log->api_response, 160) }}</td>
                        @endif
                        <td class="text-nowrap">{{ formatDateTime($log->created_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">@lang('me::me.no_sms_logs_found')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
@endsection
