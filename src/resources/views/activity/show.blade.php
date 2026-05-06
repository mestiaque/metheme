@extends('me::app')

@section('title', 'Activity Details')
@section('meta-title', 'Activity Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('me.activity.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Activity Details') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Activity ID:') }}</strong><br>
                                {{ $activity->id }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Status:') }}</strong><br>
                                <span class="badge bg-{{ $activity->getStatusColor() }}">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Activity Type:') }}</strong><br>
                                <span class="badge bg-info">{{ $activity->getActivityTypeLabel() }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Date/Time:') }}</strong><br>
                                {{ $activity->activity_at->format('Y-m-d H:i:s') }}
                            </p>
                        </div>
                    </div>

                    @if($activity->description)
                        <div class="row mb-3">
                            <div class="col-12">
                                <p>
                                    <strong>{{ __('Description:') }}</strong><br>
                                    {{ $activity->description }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <h5 class="mb-3">{{ __('User Information') }}</h5>
                    @if($activity->user)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('User Name:') }}</strong><br>
                                    {{ $activity->user->name }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('Email:') }}</strong><br>
                                    {{ $activity->user->email ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('Phone:') }}</strong><br>
                                    {{ $activity->user->phone ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">{{ __('Guest User') }}</p>
                    @endif

                    <hr>

                    <h5 class="mb-3">{{ __('Connection Details') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('IP Address:') }}</strong><br>
                                <code class="text-primary">{{ $activity->ip_address }}</code>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">{{ __('Browser & Device Information') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Browser:') }}</strong><br>
                                {{ $activity->browser_name }} (v{{ $activity->browser_version }})
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Operating System:') }}</strong><br>
                                {{ $activity->os_name }} (v{{ $activity->os_version }})
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Device Type:') }}</strong><br>
                                <span class="badge bg-secondary">{{ ucfirst($activity->device_type) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>{{ __('Device Name:') }}</strong><br>
                                {{ $activity->device_name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">{{ __('Technical Details') }}</h5>
                    <div class="mb-3">
                        <p>
                            <strong>{{ __('User Agent:') }}</strong><br>
                            <small class="text-muted d-block" style="word-break: break-all;">
                                {{ $activity->user_agent }}
                            </small>
                        </p>
                    </div>

                    @if($activity->latitude && $activity->longitude)
                        <hr>
                        <h5 class="mb-3">{{ __('Location') }}</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('Country:') }}</strong><br>
                                    {{ $activity->country ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('City:') }}</strong><br>
                                    {{ $activity->city ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('Latitude:') }}</strong><br>
                                    {{ $activity->latitude }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>{{ __('Longitude:') }}</strong><br>
                                    {{ $activity->longitude }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted small">
                                {{ __('Created:') }} {{ $activity->created_at->format('Y-m-d H:i:s') }}<br>
                                {{ __('Last Updated:') }} {{ $activity->updated_at->format('Y-m-d H:i:s') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Quick Info') }}</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('User') }}</span>
                            <span class="info-box-number">{{ $activity->user?->name ?? __('Guest') }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-laptop"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Device') }}</span>
                            <span class="info-box-number">{{ ucfirst($activity->device_type) }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-globe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('IP Address') }}</span>
                            <span class="info-box-number" style="font-size: 12px;">{{ $activity->ip_address }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Activity Time') }}</span>
                            <span class="info-box-number">{{ $activity->activity_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-box-content {
        padding-left: 10px;
    }

    .info-box-number {
        font-weight: 700;
        display: block;
        font-size: 18px;
    }

    .info-box-text {
        display: block;
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #777;
        text-transform: uppercase;
    }
</style>
@endsection
