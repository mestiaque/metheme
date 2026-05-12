@extends('me::master')

@section('title', 'User Activities')
@section('meta-title', 'User Activities')


@push('buttons')
    <a href="{{ route('me.activity.export', request()->query()) }}" class="btn btn-encodex-print btn-sm">
        <i class="fas fa-download"></i> {{ __('Export CSV') }}
    </a>
@endpush

@section('content')
<div class="">

    <!-- Activities Table -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('me.activity.index') }}" class="mb-4 glass-search-form">
                <div class="row g-3 p-2">
                    <div class="col-md-2 mt-2">
                        <label for="search" class="form-label mb-0">{{ __('Search User') }}</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search" 
                               placeholder="{{ __('Name, Email, Phone') }}" value="{{ request('search') }}">
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="activity_type" class="form-label mb-0">{{ __('Activity Type') }}</label>
                        <select class="form-control form-control-sm" id="activity_type" name="activity_type">
                            <option value="">{{ __('All Activities') }}</option>
                            @foreach($activityTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('activity_type') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="status" class="form-label mb-0">{{ __('Status') }}</label>
                        <select class="form-control form-control-sm" id="status" name="status">
                            <option value="">{{ __('All Status') }}</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="device_type" class="form-label mb-0">{{ __('Device Type') }}</label>
                        <select class="form-control form-control-sm" id="device_type" name="device_type">
                            <option value="">{{ __('All Devices') }}</option>
                            @foreach($deviceTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('device_type') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="date_from" class="form-label mb-0">{{ __('Date From') }}</label>
                        <input type="date" class="form-control form-control-sm" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="date_to" class="form-label mb-0">{{ __('Date To') }}</label>
                        <input type="date" class="form-control form-control-sm" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="ip_address" class="form-label mb-0">{{ __('IP Address') }}</label>
                        <input type="text" class="form-control form-control-sm" id="ip_address" name="ip_address" 
                               placeholder="{{ __('e.g., 192.168') }}" value="{{ request('ip_address') }}">
                    </div>
    
                    <div class="col-md-2 mt-2">
                        <label for="browser_name" class="form-label mb-0">{{ __('Browser') }}</label>
                        <input type="text" class="form-control form-control-sm" id="browser_name" name="browser_name" 
                               placeholder="{{ __('Chrome, Firefox, Safari') }}" value="{{ request('browser_name') }}">
                    </div>
    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-encodex-search btn-sm me-2">
                            <i class="fas fa-search"></i> {{ __('Search') }}
                        </button>
                        <a href="{{ route('me.activity.index') }}" class="btn btn-encodex-clear btn-sm">
                             <i class="fas fa-redo"></i> {{ __('Reset') }}
                        </a>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-encodex table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">{{ __('User') }}</th>
                            <th style="width: 12%">{{ __('Activity') }}</th>
                            <th style="width: 10%">{{ __('Device') }}</th>
                            <th style="width: 15%">{{ __('Browser') }}</th>
                            <th style="width: 12%">{{ __('IP Address') }}</th>
                            <th style="width: 8%">{{ __('Status') }}</th>
                            <th style="width: 15%">{{ __('Date/Time') }}</th>
                            <th style="width: 8%">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activity->id }}</td>
                                <td>
                                    @if($activity->user)
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong>{{ $activity->user->name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $activity->user->email ?? $activity->user->phone }}
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-encodex bg-secondary">Guest</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-encodex bg-info text-white">
                                        {{ $activity->getActivityTypeLabel() }}
                                    </span>
                                </td>
                                <td> 
                                    <div>
                                        <small class="d-block">
                                            @if($activity->device_type === 'mobile')
                                                @if($activity->device_name == "iPhone")
                                                    <i class="fas fa-mobile"></i>
                                                @else
                                                    <i class="fas fa-mobile-alt"></i>
                                                @endif
                                            @elseif($activity->device_type === 'tablet')
                                                <i class="fas fa-tablet-alt"></i>
                                            @else
                                                <i class="fas fa-desktop"></i> 
                                            @endif
                                            {{ ucfirst($activity->device_type) }}
                                        </small>
                                        <small class="text-muted">{{ $activity->device_name ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="d-block">
                                            @if($activity->browser_name == "Chrome")
                                                <i class="fab fa-chrome"></i>
                                            @elseif($activity->browser_name == "Firefox")
                                                <i class="fab fa-firefox"></i>
                                            @elseif($activity->browser_name == "Safari")
                                                <i class="fab fa-safari"></i>
                                            @elseif($activity->browser_name == "Edge")
                                                <i class="fab fa-edge"></i>
                                            @else
                                                <i class="fas fa-globe"></i>
                                            @endif
                                            {{ $activity->browser_name }} {{ $activity->browser_version }}
                                        </small>
                                        <small class="text-muted">
                                            @if($activity->os_name == "Windows")
                                                <i class="fab fa-windows"></i>
                                            @elseif($activity->os_name == "macOS")
                                                <i class="fab fa-apple"></i>
                                            @elseif($activity->os_name == "Linux")
                                                <i class="fab fa-linux"></i>
                                            @elseif($activity->os_name == "Android")
                                                <i class="fab fa-android"></i>
                                            @elseif($activity->os_name == "iOS")
                                                <i class="fab fa-apple"></i>
                                            @else
                                                <i class="fas fa-desktop"></i>
                                            @endif
                                            {{ $activity->os_name }} {{ $activity->os_version }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-primary">{{ $activity->ip_address }}</code>
                                </td>
                                <td>
                                    @if($activity->status === 'success')
                                        <span class="badge badge-encodex bg-success text-white">{{ __('Success') }}</span>
                                    @elseif($activity->status === 'failed')
                                        <span class="badge badge-encodex bg-danger text-white">{{ __('Failed') }}</span>
                                    @else
                                        <span class="badge badge-encodex bg-warning text-white">{{ __('Pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <small class="d-block">
                                            {{ formatDate($activity->activity_at) }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $activity->activity_at->format('H:i:s A') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex" style="gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-encodex-show" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $activity->id }}"
                                                title="{{ __('Show Details') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @php
                                            $isOwnLoginActivity =
                                                (int) $activity->user_id === (int) auth()->id()
                                                && $activity->activity_type === 'login'
                                                && $activity->status === 'success';
                                        @endphp

                                        @if($isOwnLoginActivity)
                                            <form method="POST" action="{{ route('me.activity.logout-device', $activity->id) }}"
                                                  onsubmit="return confirm('{{ __('Are you sure you want to logout this device?') }}');">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-encodex-delete"
                                                        title="{{ __('Logout Device') }}">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Detail Modal -->
                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title d-flex align-items-center">
                                                        <i class="fas fa-info-circle me-2 text-primary"></i> {{ __('Activity Details') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row g-4">
                                                        <!-- Left Column -->
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-bolt w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Activity Type') }}</small>
                                                                    <span class="badge badge-encodex bg-info text-white">
                                                                        {{ $activity->getActivityTypeLabel() }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-user w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('User') }}</small>
                                                                    <strong>{{ $activity->user?->name ?? 'Guest' }}</strong>
                                                                    <div class="text-muted small">{{ $activity->user?->email ?? $activity->user?->phone ?? 'N/A' }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-network-wired w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Browser Information') }}</small>
                                                                    @if($activity->browser_name)
                                                                        <span>
                                                                            @if($activity->browser_name == "Chrome")
                                                                                <i class="fab fa-chrome"></i>
                                                                            @elseif($activity->browser_name == "Firefox")
                                                                                <i class="fab fa-firefox"></i>
                                                                            @elseif($activity->browser_name == "Safari")
                                                                                <i class="fab fa-safari"></i>
                                                                            @elseif($activity->browser_name == "Edge")
                                                                                <i class="fab fa-edge"></i>
                                                                            @else
                                                                                <i class="fas fa-globe"></i>
                                                                            @endif
                                                                            {{ $activity->browser_name }} {{ $activity->browser_version }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-network-wired w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('IP Address') }}</small>
                                                                    <code class="text-primary fw-bold">{{ $activity->ip_address }}</code>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Right Column -->
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-check-circle w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Status') }}</small>
                                                                    <span class="badge badge-encodex bg-{{ $activity->getStatusColor() }} text-white">
                                                                        {{ ucfirst($activity->status) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-calendar-alt w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Date & Time') }}</small>
                                                                    <strong>{{ formatDate($activity->activity_at) }}</strong>
                                                                    <div class="text-muted small">{{ $activity->activity_at->format('H:i:s A') }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-laptop w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Device Type') }}</small>
                                                                    <span>
                                                                            @if($activity->device_type === 'mobile')
                                                                                @if($activity->device_name == "iPhone")
                                                                                    <i class="fas fa-mobile"></i>
                                                                                @else
                                                                                    <i class="fas fa-mobile-alt"></i>
                                                                                @endif
                                                                            @elseif($activity->device_type === 'tablet')
                                                                                <i class="fas fa-tablet-alt"></i>
                                                                            @else
                                                                                <i class="fas fa-desktop"></i>
                                                                            @endif
                                                                        {{ ucfirst($activity->device_type) }} 
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0 bg-light p-2 rounded text-primary me-3">
                                                                    <i class="fas fa-laptop w-20px"></i>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">{{ __('Operating System') }}</small>
                                                                    <span>
                                                                        @if($activity->os_name == "Windows")
                                                                            <i class="fab fa-windows"></i>
                                                                        @elseif($activity->os_name == "macOS")
                                                                            <i class="fab fa-apple"></i>
                                                                        @elseif($activity->os_name == "Linux")
                                                                            <i class="fab fa-linux"></i>
                                                                        @elseif($activity->os_name == "Android")
                                                                            <i class="fab fa-android"></i>
                                                                        @elseif($activity->os_name == "iOS")
                                                                            <i class="fab fa-apple"></i>
                                                                        @else
                                                                             <i class="fas fa-desktop"></i>
                                                                        @endif
                                                                        {{ $activity->os_name }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-0" style="    padding-right: calc(var(--bs-gutter-x) * 0.5); padding-left: calc(var(--bs-gutter-x) * 0.5);">
                                                        <h6 class="fw-bold"><i class="fas fa-fingerprint me-2 text-primary"></i>{{ __('User Agent') }}</h6>
                                                        <div class="p-2 bg-dark text-light rounded small" style="word-break: break-all; font-family: monospace;">
                                                            {{ $activity->user_agent }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-encodex-delete btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                                    <p class="text-muted mt-3">{{ __('No activities found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activities->hasPages())
                <div class="mt-3">
                    {{ $activities->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .modal-body .row > div {
        margin-bottom: 1rem;
    }

    .modal-body p {
        margin-bottom: 0.5rem;
    }

    .badge-encodex {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(25, 46, 235, 0.95); /* টেক্সট কালার */
        border-radius: 50px; /* পিল শেপ */
        
        /* ব্যাকগ্রাউন্ড ব্লার এবং ট্রান্সপারেন্সি */
        background: rgba(255, 255, 255, 0.15); 
        backdrop-filter: blur(15px) saturate(160%); 
        -webkit-backdrop-filter: blur(15px) saturate(160%);
        
        /* ইনার শ্যাডো এবং হাইলাইট (Liquid লুকের জন্য) */
        box-shadow: 
            0 4px 15px rgba(0, 0, 0, 0.1), 
            inset 0 1px 1px rgba(255, 255, 255, 0.5), 
            inset 0 -1px 5px rgba(255, 255, 255, 0.1);
            
        transition: all 0.3s ease;
        cursor: context-menu;
    }

    /* হোভার ইফেক্ট */
    .badge-encodex:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05); /* হালকা স্কেলিং */
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

</style>

@endsection
