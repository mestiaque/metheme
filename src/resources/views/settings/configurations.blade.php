@extends('me::master')

@section('title', trans('Shop Settings'))

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('me.configurations.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Pagination Settings --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="fas fa-list-ol me-1"></i> @lang('Table Display Settings')
                                </h6>
                            </div>
                            <div class="card-body">
                                <label for="pagination" class="form-label fw-semibold">
                                    @lang('Results per page')
                                </label>
                                <input type="number" min="1" class="form-control form-control-sm"
                                       id="pagination" name="pagination"
                                       value="{{ old('pagination', $settings['pagination']) }}">
                                <small class="text-muted">
                                    @lang('Controls how many records will be shown per page')
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Other Settings --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="fas fa-cog me-1"></i> @lang('Other Settings')
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="enable_translation"
                                           name="enable_translation" {{ $settings['enable_translation'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_translation">
                                        @lang('Enable Translation')
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- URL Settings --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="fas fa-link me-1"></i> @lang('URL Configuration')
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="root_url" class="form-label fw-semibold">@lang('Root URL')</label>
                                        <input type="url" class="form-control form-control-sm" id="root_url"
                                               name="root_url" value="{{ old('root_url', $settings['root_url']) }}"
                                               placeholder="https://example.com">
                                        <small class="text-muted">@lang('Main website root link')</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="profile_url" class="form-label fw-semibold">@lang('Profile URL')</label>
                                        <input type="url" class="form-control form-control-sm" id="profile_url"
                                               name="profile_url" value="{{ old('profile_url', $settings['profile_url']) }}"
                                               placeholder="https://example.com/profile">
                                        <small class="text-muted">@lang('User profile page link')</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="setting_url" class="form-label fw-semibold">@lang('Setting URL')</label>
                                        <input type="url" class="form-control form-control-sm" id="setting_url"
                                               name="setting_url" value="{{ old('setting_url', $settings['setting_url']) }}"
                                               placeholder="https://example.com/settings">
                                        <small class="text-muted">@lang('System settings page link')</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="logout_url" class="form-label fw-semibold">@lang('Logout URL')</label>
                                        <input type="url" class="form-control form-control-sm" id="logout_url"
                                               name="logout_url" value="{{ old('logout_url', $settings['logout_url']) }}"
                                               placeholder="https://example.com/logout">
                                        <small class="text-muted">@lang('Logout route link')</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="login_url" class="form-label fw-semibold">@lang('Login URL')</label>
                                        <input type="url" class="form-control form-control-sm" id="login_url"
                                               name="login_url" value="{{ old('login_url', $settings['login_url']) }}"
                                               placeholder="https://example.com/login">
                                        <small class="text-muted">@lang('Login page link')</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-encodex px-4">
                        <i class="fas fa-save me-1"></i> @lang('Save Settings')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
