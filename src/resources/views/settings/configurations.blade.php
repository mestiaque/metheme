@extends('me::master')

@section('title', trans('me::me.Shop Settings'))

@section('content')
    <div class="card glass-card">
        <form method="POST" action="{{ route('configurations.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Pagination Settings --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-primary fw-semibold">
                                <i class="fas fa-list-ol me-1"></i> @lang('me::me.Table Display Settings')
                            </h6>
                        </div>
                        <div class="card-body">
                            <label for="pagination" class="form-label fw-semibold">
                                @lang('me::me.Results per page')
                            </label>
                            <input type="number" min="1" class="form-control form-control-sm"
                                    id="pagination" name="pagination"
                                    value="{{ old('pagination', $settings['pagination']) }}">
                            <small class="text-muted">
                                @lang('me::me.Controls how many records will be shown per page')
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Other Settings --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-primary fw-semibold">
                                <i class="fas fa-cog me-1"></i> @lang('me::me.Other Settings')
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="enable_translation"
                                        name="enable_translation" {{ $settings['enable_translation'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_translation">
                                    @lang('me::me.Enable Translation')
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="enable_registration"
                                        name="enable_registration" {{ $settings['enable_registration'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_registration">
                                    @lang('me::me.Enable Registration')
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="enable_forget_password"
                                        name="enable_forget_password" {{ $settings['enable_forget_password'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_forget_password">
                                    @lang('me::me.Enable Forget Password')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Company Logo Settings --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-primary fw-semibold">
                                <i class="fas fa-cog me-1"></i> @lang('me::me.Logo Settings')
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group text-center">
                                <label class="font-weight-bold text-primary d-block">@lang('me::me.App Logo')</label>
                                <div class="logo-preview mb-3" style="height: 10rem">
                                    @if($settings['app_logo'])
                                        <img loading="lazy" src="{{ asset('storage/images/app_logo/' . $settings['app_logo']) }}"
                                                alt="App Logo" class="img-thumbnail" style="max-height: 150px;">
                                    @else
                                        <div class="empty-logo p-4 bg-light text-center border rounded">
                                            <i class="fas fa-image fa-2x text-gray-400"></i>
                                            <p class="mt-2 text-gray-500">@lang('me::me.No image uploaded')</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="border border-primary custom-file-input @error('app_logo') is-invalid @enderror"
                                        name="app_logo" accept="image/*">
                                @error('app_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">@lang('me::me.Recommended size: 200x200px, Max: 4MB')</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Favicon Settings --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-primary fw-semibold">
                                <i class="fas fa-cog me-1"></i> @lang('me::me.Favicon(Ico) Settings')
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group text-center">
                                <label class="font-weight-bold text-primary d-block">@lang('me::me.Favicon (ICO)')</label>
                                <div class="logo-preview mb-3" style="height: 10rem">
                                    @if($settings['app_ico'])
                                        <img loading="lazy" src="{{ get_image('app_ico') }}"
                                                alt="Favicon" class="img-thumbnail" style="max-height: 100px;">
                                    @else
                                        <div class="empty-logo p-4 bg-light text-center border rounded">
                                            <i class="fas fa-image fa-2x text-gray-400"></i>
                                            <p class="mt-2 text-gray-500">@lang('me::me.No image uploaded')</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="border border-primary custom-file-input @error('app_ico') is-invalid @enderror"
                                        name="app_ico" accept="image/*">
                                @error('app_ico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">@lang('me::me.Recommended size: 64x64px, Max: 4MB')</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-encodex px-4">
                    <i class="fas fa-save me-1"></i> @lang('me::me.Save Settings')
                </button>
            </div>
        </form>
    </div>
@endsection
