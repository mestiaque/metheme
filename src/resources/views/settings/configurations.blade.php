@extends('me::master')

@section('title', trans('me::me.App Configurations'))

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
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="show_settings_link"
                                        name="show_settings_link" {{ $settings['show_settings_link'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_settings_link">
                                    @lang('me::me.Show Settings Link in Profile Menu')
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="enable_page_loader"
                                        name="enable_page_loader" {{ $settings['enable_page_loader'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_page_loader">
                                    @lang('me::me.enable_page_loader')
                                </label>
                            </div>
                            {{-- Which loader design (me_settings.page_loaders); only matters while the loader is on --}}
                            <div class="mb-3 ms-4" id="page_loader_wrap" style="{{ $settings['enable_page_loader'] ? '' : 'display: none;' }}">
                                <label for="page_loader" class="form-label small fw-semibold mb-1">@lang('me::me.page_loader')</label>
                                <select name="page_loader" id="page_loader" class="form-select form-select-sm @error('page_loader') is-invalid @enderror">
                                    @foreach (array_keys(config('me_settings.page_loaders', [])) as $loaderKey)
                                        <option value="{{ $loaderKey }}" {{ old('page_loader', $settings['page_loader']) === $loaderKey ? 'selected' : '' }}>
                                            @lang('me::me.loader_' . str_replace('-', '_', $loaderKey))
                                        </option>
                                    @endforeach
                                </select>
                                @error('page_loader') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @push('js')
                            <script>
                                document.getElementById('enable_page_loader').addEventListener('change', function () {
                                    document.getElementById('page_loader_wrap').style.display = this.checked ? '' : 'none';
                                });
                            </script>
                            @endpush
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
                                        <img loading="lazy" src="{{ get_image('app_logo') }}"
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

                {{-- Logo in the top header and in the page title bar: on/off + own image (no image: header empty, title bar shows an icon) --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-primary fw-semibold">
                                <i class="fas fa-image me-1"></i> @lang('me::me.header_and_title_logo')
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">@lang('me::me.header_and_title_logo_hint')</p>
                            <div class="row g-4">
                                @foreach ([
                                    ['key' => 'header_logo', 'switch' => 'show_header_logo', 'height' => '2.5rem', 'empty' => 'header_logo_empty', 'emptyIcon' => 'fas fa-ban'],
                                    ['key' => 'title_logo', 'switch' => 'show_title_logo', 'height' => '2rem', 'empty' => 'title_logo_icon', 'emptyIcon' => 'bi bi-bluesky'],
                                ] as $logo)
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="{{ $logo['switch'] }}"
                                                    name="{{ $logo['switch'] }}" {{ $settings[$logo['switch']] ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="{{ $logo['switch'] }}">
                                                @lang('me::me.' . $logo['switch'])
                                            </label>
                                        </div>

                                        <label class="font-weight-bold text-primary d-block">@lang('me::me.' . $logo['key'])</label>
                                        <div class="logo-preview border rounded bg-light d-flex align-items-center justify-content-center mb-2" style="height: 5rem">
                                            @if($settings[$logo['key']])
                                                <img loading="lazy" src="{{ get_image($logo['key']) }}" alt="" style="max-height: {{ $logo['height'] }}; width: auto;">
                                            @else
                                                <span class="small text-muted"><i class="{{ $logo['emptyIcon'] }} me-1"></i> @lang('me::me.' . $logo['empty'])</span>
                                            @endif
                                        </div>
                                        <input type="file" class="border border-primary custom-file-input @error($logo['key']) is-invalid @enderror"
                                                name="{{ $logo['key'] }}" accept="image/*">
                                        @error($logo['key'])
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @if($settings[$logo['key']])
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="remove_{{ $logo['key'] }}" name="remove_{{ $logo['key'] }}">
                                                <label class="form-check-label small text-danger" for="remove_{{ $logo['key'] }}">
                                                    @lang('me::me.remove_logo_image')
                                                </label>
                                            </div>
                                        @endif
                                        <small class="form-text text-muted d-block">@lang('me::me.logo_image_size_hint', ['height' => $logo['height']])</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-savex px-4">
                    <i class="fas fa-save me-1"></i> @lang('me::me.Save Settings')
                </button>
            </div>
        </form>
    </div>
@endsection
