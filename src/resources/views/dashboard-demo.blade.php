@extends('me::master')
@section('title', __('me::me.Dashboard'))

@push('css')
<style>
    .me-stat {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .me-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15, 45, 74, .12); }
    .me-stat .me-stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; color: #fff;
    }
    .me-stat .me-stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1.1; }
    .me-stat .me-stat-label { font-size: .8rem; color: #6c757d; text-transform: uppercase; letter-spacing: .03em; }
    .me-stat .me-stat-sub { font-size: .78rem; color: #6c757d; }
    .me-grad-1 { background: linear-gradient(135deg, #4e73df, #224abe); }
    .me-grad-2 { background: linear-gradient(135deg, #1cc88a, #13855c); }
    .me-grad-3 { background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .me-grad-4 { background: linear-gradient(135deg, #e74a3b, #be2617); }
    .me-grad-5 { background: linear-gradient(135deg, #36b9cc, #258391); }
    .me-grad-6 { background: linear-gradient(135deg, #858796, #60616f); }
    .me-panel { border: 0; border-radius: 16px; }
    .me-panel .card-header { background: transparent; border-bottom: 1px solid rgba(0,0,0,.06); font-weight: 600; }
    .me-quick a { border-radius: 12px; }
    .me-role-bar { height: 8px; border-radius: 8px; background: rgba(78,115,223,.15); overflow: hidden; }
    .me-role-bar > span { display: block; height: 100%; background: linear-gradient(90deg, #4e73df, #36b9cc); }
</style>
@endpush

@section('content')
@php
    $cards = [
        ['label' => __('me::me.dash_total_users'),      'value' => $stats['users'],            'sub' => __('me::me.dash_active_count', ['count' => toBanglaNumber($stats['active_users'])]), 'icon' => 'fas fa-users',          'grad' => 'me-grad-1'],
        ['label' => __('me::me.Roles'),                 'value' => $stats['roles'],            'sub' => __('me::me.dash_roles_sub'),                                                        'icon' => 'fas fa-user-shield',    'grad' => 'me-grad-5'],
        ['label' => __('me::me.dash_logins_today'),     'value' => $stats['logins_today'],     'sub' => __('me::me.dash_failed_count', ['count' => toBanglaNumber($stats['failed_today'])]),  'icon' => 'fas fa-sign-in-alt',    'grad' => 'me-grad-2'],
        ['label' => __('me::me.dash_activities_today'), 'value' => $stats['activities_today'], 'sub' => __('me::me.dash_activities_sub'),                                                   'icon' => 'fas fa-history',        'grad' => 'me-grad-3'],
        ['label' => __('me::me.dash_mail_month'),       'value' => $stats['mail_month'],       'sub' => __('me::me.dash_this_month'),                                                       'icon' => 'fas fa-envelope',       'grad' => 'me-grad-6'],
        ['label' => __('me::me.dash_sms_month'),        'value' => $stats['sms_month'],        'sub' => __('me::me.dash_this_month'),                                                       'icon' => 'fas fa-sms',            'grad' => 'me-grad-4'],
    ];
    $maxRole = max(1, (int) ($roleDistribution->max('total') ?? 0));
@endphp

<div class="container-fluid px-0 mt-3">

    {{-- Welcome --}}
    <div class="card me-panel glass-card mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h5 class="mb-1 fw-bold">{{ __('me::me.dash_welcome', ['name' => auth()->user()->name]) }}</h5>
                <div class="text-muted small">{{ formatDate(now(), 'l, d M Y') }}</div>
            </div>
            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                <i class="fas fa-circle text-success me-1" style="font-size:.55rem"></i> {{ __('me::me.dash_system_online') }}
            </span>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-3">
        @foreach($cards as $card)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card me-stat glass-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="me-stat-label">{{ $card['label'] }}</div>
                            <div class="me-stat-icon {{ $card['grad'] }}"><i class="{{ $card['icon'] }}"></i></div>
                        </div>
                        <div class="me-stat-value">{{ toBanglaNumber($card['value']) }}</div>
                        <div class="me-stat-sub mt-1">{{ $card['sub'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-3">
        {{-- Activity chart --}}
        <div class="col-lg-8">
            <div class="card me-panel glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-chart-area me-1 text-primary"></i> {{ __('me::me.dash_activity_7_days') }}</span>
                </div>
                <div class="card-body">
                    <div id="meActivityChart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Users by role --}}
        <div class="col-lg-4">
            <div class="card me-panel glass-card h-100">
                <div class="card-header">
                    <i class="fas fa-user-tag me-1 text-info"></i> {{ __('me::me.dash_users_by_role') }}
                </div>
                <div class="card-body">
                    @forelse($roleDistribution as $role)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold">{{ $role->name }}</span>
                                <span class="text-muted">{{ toBanglaNumber($role->total) }}</span>
                            </div>
                            <div class="me-role-bar"><span style="width: {{ round(($role->total / $maxRole) * 100) }}%"></span></div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">{{ __('me::me.dash_no_data') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Recent activity --}}
        <div class="col-lg-8">
            <div class="card me-panel glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-stream me-1 text-warning"></i> {{ __('me::me.dash_recent_activity') }}</span>
                    @if(Route::has('activity.index') && auth()->user()->can('me_activity.view'))
                        <a href="{{ route('activity.index') }}" class="btn btn-sm btn-encodex-list rounded">{{ __('me::me.View All') }}</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">{{ __('me::me.dash_col_user') }}</th>
                                    <th>{{ __('me::me.dash_col_activity') }}</th>
                                    <th>{{ __('me::me.dash_col_status') }}</th>
                                    <th class="text-end pe-3">{{ __('me::me.dash_col_time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                    <tr>
                                        <td class="ps-3">{{ $activity->user?->name ?? __('me::me.dash_guest') }}</td>
                                        <td>{{ $activity->getActivityTypeLabel() }}</td>
                                        <td><span class="badge bg-{{ $activity->getStatusColor() }}">{{ ucfirst($activity->status) }}</span></td>
                                        <td class="text-end pe-3 text-muted small">{{ $activity->activity_at?->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">{{ __('me::me.dash_no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick links (only what the user may open) --}}
        <div class="col-lg-4">
            <div class="card me-panel glass-card h-100">
                <div class="card-header">
                    <i class="fas fa-bolt me-1 text-danger"></i> {{ __('me::me.dash_quick_links') }}
                </div>
                <div class="card-body me-quick">
                    @php
                        $links = [
                            ['route' => 'users.create',        'permit' => 'me_user.create',            'icon' => 'fas fa-user-plus',  'label' => __('me::me.dash_add_user')],
                            ['route' => 'roles.create',        'permit' => 'me_role.create',            'icon' => 'fas fa-user-shield','label' => __('me::me.dash_add_role')],
                            ['route' => 'configurations.edit', 'permit' => 'me_setting.configurations', 'icon' => 'fas fa-wrench',     'label' => __('me::me.Configurations')],
                            ['route' => 'mail-config.edit',    'permit' => 'me_setting.mail',           'icon' => 'fas fa-at',         'label' => __('me::me.Mail Configuration')],
                            ['route' => 'sms-config.edit',     'permit' => 'me_setting.sms',            'icon' => 'fas fa-sms',        'label' => __('me::me.SMS Configuration')],
                            ['route' => 'profile.edit',        'permit' => null,                        'icon' => 'fas fa-id-badge',   'label' => __('me::me.dash_my_profile')],
                        ];
                        $links = array_filter($links, fn ($l) => Route::has($l['route']) && (!$l['permit'] || auth()->user()->can($l['permit'])));
                    @endphp
                    <div class="row g-2">
                        @forelse($links as $link)
                            <div class="col-6">
                                <a href="{{ route($link['route']) }}" class="btn btn-light border w-100 text-start py-2">
                                    <i class="{{ $link['icon'] }} me-1 text-primary"></i> <span class="small">{{ $link['label'] }}</span>
                                </a>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">{{ __('me::me.dash_no_data') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof ApexCharts === 'undefined') return;

        new ApexCharts(document.querySelector('#meActivityChart'), {
            chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [
                { name: @json(__('me::me.dash_series_activities')), data: @json($chart['activities']) },
                { name: @json(__('me::me.dash_series_logins')), data: @json($chart['logins']) },
            ],
            xaxis: { categories: @json($chart['labels']) },
            yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
            colors: ['#4e73df', '#1cc88a'],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
            dataLabels: { enabled: false },
            legend: { position: 'top', horizontalAlign: 'right' },
            grid: { borderColor: 'rgba(0,0,0,.06)' },
        }).render();
    });
</script>
@endpush
