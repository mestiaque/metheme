<!--begin::Sidebar-->
<!--begin::Sidebar-->
<aside class="app-sidebar sidebar-glass bg-encodexx shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    @php $url = me_home_url(); @endphp
    <a href="{{ $url }}" class="brand-link">
    @php $logo = (get_setting('app_logo')) ? get_image('app_logo') : asset('assets/img/favicon/Encodex.ico'); @endphp
      <img src="{{ $logo }}" class="brand-image opacity-75" alt="M. ESTIAQUE">
      {{-- <span class="brand-text hide-mobile fw-light">{{ get_setting('shop_name', 'ENCODEX') }}</span> --}}
    </a>
  </div>



    <div class="sidebar-wrapper">
        <nav class="">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

                {{-- <li class="company-text-sidebar">{{ get_setting('shop_name', 'M. ESTIAQUE') }}</li> --}}
                @php
                    $fullName = get_setting('app_name', 'M. ESTIAQUE');
                    $words = explode(" ", $fullName);
                    $shortName = "";
                    foreach ($words as $w) {
                        $shortName .= mb_substr($w, 0, 1);
                    }
                @endphp

                <li class="company-text-sidebar">
                    <span class="full-text">{{ $fullName }}</span>
                    <span class="short-text">{{ strtoupper($shortName) }}</span>
                </li>

                @php
                    // sidebar config টি সংগ্রহ করে sl অনুযায়ী সর্ট করা হচ্ছে
                    $sidebarMenu = collect(config('sidebar'))->sortBy('sl')->toArray();

                    // for_active thakle sheta check korbe, na thakle route*; "params" thakle segulo o milte hobe
                    $menuActive = function ($item) {
                        try {
                            $pattern = isset($item['for_active']) ? $item['for_active'] . '*' : $item['route'] . '*';
                            if (!request()->routeIs($pattern)) {
                                return false;
                            }
                            foreach ($item['params'] ?? [] as $key => $value) {
                                if ((string) request()->route($key) !== (string) $value) {
                                    return false;
                                }
                            }
                            return true;
                        } catch (\Exception $e) {
                            return false;
                        }
                    };
                @endphp

                @foreach($sidebarMenu as $item)
                    @if(isset($item['header']))
                        <li class="nav-header">
                            <i class="{{ $item['icon'] ?? 'bi bi-grid-3x3-gap-fill' }} me-2"></i>
                            {{ mb_strtoupper(menu_trans($item['header'])) }}
                        </li>
                    @elseif(isset($item['children']))
                        @php
                            $visibleChildren = collect($item['children'])->filter(function($child) {
                                if (isset($child['permit']) && !auth()->user()->can($child['permit'])) {
                                    return false;
                                }
                                try {
                                    route($child['route'], $child['params'] ?? []);
                                    return true;
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });

                            $isParentActive = $visibleChildren->contains($menuActive);
                        @endphp

                        @if($visibleChildren->isNotEmpty())
                            <li class="parentnav nav-item {{ $isParentActive ? 'menu-open' : '' }} {{ (isset($item['active']) && $item['active'] === true) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isParentActive ? 'active' : '' }}">
                                    <i class="nav-icon {{ $item['icon'] }} {{ $item['icon_color'] ?? 'default-sidebar-icon' }}"></i>
                                    <p>
                                        {{ menu_trans($item['title']) }}
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($visibleChildren as $child)
                                        @php $isChildActive = $menuActive($child); @endphp
                                        <li class="nav-item">
                                            <a href="{{ route($child['route'], $child['params'] ?? []) }}" class="nav-link {{ $isChildActive ? 'active' : '' }} m-1">
                                                <i class="nav-icon {{ $child['icon'] }} {{ $child['icon_color'] ?? 'text-muted' }}"></i>
                                                <p>{{ menu_trans($child['title']) }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        @php
                            $canAccess = !isset($item['permit']) || auth()->user()->can($item['permit']);
                            $routeExists = false;
                            try {
                                route($item['route'], $item['params'] ?? []);
                                $routeExists = true;
                            } catch (\Exception $e) {}

                            $isActive = $routeExists && $menuActive($item);
                        @endphp

                        @if($canAccess && $routeExists)
                            <li class="parentnav nav-item {{ $isActive ? 'menu-open' : '' }}">
                                <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                                    <i class="nav-icon {{ $item['icon'] }} {{ $item['icon_color'] ?? 'text-primary' }}"></i>
                                    <p>{{ menu_trans($item['title']) }}</p>
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var sidebarWrapper = document.querySelector('.app-sidebar .sidebar-wrapper');
    if (!sidebarWrapper) return;

    var activeLink = sidebarWrapper.querySelector('.nav-link.active');

    if (activeLink) {
        var activeLinkTop = activeLink.offsetTop;
        var sidebarHeight = sidebarWrapper.clientHeight;
        var activeLinkHeight = activeLink.clientHeight;

        sidebarWrapper.scrollTop = activeLinkTop - (sidebarHeight / 2) + (activeLinkHeight / 2);
    }

    sidebarWrapper.classList.add('sidebar-ready');
});
</script>






