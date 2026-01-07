<!--begin::Sidebar-->
<!--begin::Sidebar-->
<aside class="app-sidebar bg-encodex shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    @php $url = get_setting('root_url') ?: '/'; @endphp
    <a href="{{ $url }}" class="brand-link">
      @if(get_setting('shop_logo'))
        <img src="{{ route('shop_logo.show', get_setting('shop_logo')) }}" class="brand-image opacity-75 " alt="mESTIAQUE">
      @else
        <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}" class="brand-image opacity-75" alt="mESTIAQUE">
      @endif
      {{-- <span class="brand-text hide-mobile fw-light">{{ get_setting('shop_name', 'ENCODEX') }}</span> --}}
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="">
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="navigation"
        aria-label="Main navigation"
        data-accordion="false"
        id="navigation"
      >
        <li class="company-text-sidebar">{{ get_setting('shop_name', 'mESTIAQUE') }}</li>
        @foreach(config('sidebar') as $item)
          @if(isset($item['header']))
            <li class="nav-header">
              <i class="{{ $item['icon'] ?? 'bi bi-grid-3x3-gap-fill' }} me-2"></i>
              {{ strtoupper(__($item['header'])) }}
            </li>
          @elseif(isset($item['children']))
            @php
              $visibleChildren = collect($item['children'])->filter(function($child) {
                if (isset($child['permit']) && !auth()->user()->can($child['permit'])) {
                  return false;
                }
                try {
                  route($child['route']);
                  return true;
                } catch (\Exception $e) {
                  return false;
                }
              });

              $isParentActive = $visibleChildren->contains(function($child) {
                try {
                  return request()->routeIs($child['route'] . '*');
                } catch (\Exception $e) {
                  return false;
                }
              });
            @endphp

            @if($visibleChildren->isNotEmpty())
              <li class="parentnav nav-item {{ $isParentActive ? 'menu-open' : '' }} {{ (isset($item['active']) && $item['active'] === true) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $isParentActive ? 'active' : '' }}">
                  <i class="nav-icon {{ $item['icon'] }} {{ $item['icon_color'] ?? 'text-primary' }}"></i>
                  <p>
                    {{ __($item['title']) }}
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @foreach($visibleChildren as $child)
                    @php
                      $isChildActive = false;
                      try {
                        $isChildActive = request()->routeIs($child['route'] . '*');
                      } catch (\Exception $e) {}
                    @endphp
                    <li class="nav-item p-1">
                      <a href="{{ route($child['route']) }}" class="nav-link {{ $isChildActive ? 'active' : '' }} m-1">
                        <i class="nav-icon {{ $child['icon'] }} {{ $child['icon_color'] ?? 'text-muted' }}"></i>
                        <p>{{ __($child['title']) }}</p>
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
                route($item['route']);
                $routeExists = true;
              } catch (\Exception $e) {}

              $isActive = false;
              if ($routeExists) {
                try {
                  $isActive = request()->routeIs($item['route'] . '*');
                } catch (\Exception $e) {}
              }
            @endphp

            @if($canAccess && $routeExists)
                <li class="parentnav nav-item {{ $isActive ? 'menu-open' : '' }}">
                  <a href="{{ route($item['route']) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                    <i class="nav-icon {{ $item['icon'] }} {{ $item['icon_color'] ?? 'text-primary' }}"></i>
                    <p>{{ __($item['title']) }}</p>
                  </a>
                </li>
            @endif
          @endif
        @endforeach
      </ul>
    </nav>
  </div>
</aside>


<style>
.nav-item.menu-open > .nav-link {
  background: rgba(0,123,255,0.15);
  border-radius: 8px 8px 0 0;
}

.nav-treeview {
  background: rgba(255,255,255,0.05);
  margin: 0 8px 8px 16px;
  border-left: 2px solid #007bff;
  border-radius: 0 0 8px 8px;
  padding: 2px 0;
}

.nav-treeview .nav-link {
  padding-left: 0px;
  font-size: 14px;
  border-radius: 6px;
  margin: 2px 8px;
}

.nav-treeview .nav-link:hover {
  background-color: rgba(255,255,255,0.08);
}

.nav-treeview .nav-link.active {
  background: linear-gradient(135deg, #0f9bd6, #0056b3);
  color: white !important;
  box-shadow: 0 2px 6px rgba(0,123,255,0.25);
}

.nav-link.active i {
  color: white !important;
}

.parentnav{
    padding-top: 5px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

aside .brand-image{
    /* filter: drop-shadow(rgb(255, 255, 255) 2px 0px 6px); */
    filter:
            drop-shadow(0 0 8px white)
            drop-shadow(0 0 12px white)
            drop-shadow(0 0 16px white) !important;
    /* background: #f1efef !important; */
}

.sidebar-menu > .parentnav:first-child {
    border-top: none;
}

.company-text-sidebar{
    font-size: 1.2rem;
    text-align: center;
    margin-bottom: 0.5rem;
    color: #ffffff;
    white-space: normal !important;
}


  @media (max-width: 767px) {
      .hide-mobile {
          display: none !important;
      }
  }

</style>
<!--end::Sidebar-->


