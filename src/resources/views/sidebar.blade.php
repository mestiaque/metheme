<!--begin::Sidebar-->
<!--begin::Sidebar-->
<aside class="app-sidebar sidebar-glass bg-encodexx shadow" data-bs-theme="dark">
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
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

                {{-- <li class="company-text-sidebar">{{ get_setting('shop_name', 'mESTIAQUE') }}</li> --}}
                @php
                    $fullName = get_setting('shop_name', 'mESTIAQUE');
                    // প্রতিটি শব্দের প্রথম অক্ষর নেওয়ার লজিক (যেমন: Kazi Traders Limited -> KTL)
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
                @endphp

                @foreach($sidebarMenu as $item)
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
                                    // for_active thakle sheta check korbe, na thakle route*
                                    $pattern = isset($child['for_active']) ? $child['for_active'] . '*' : $child['route'] . '*';
                                    return request()->routeIs($pattern);
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });
                        @endphp

                        @if($visibleChildren->isNotEmpty())
                            <li class="parentnav nav-item {{ $isParentActive ? 'menu-open' : '' }} {{ (isset($item['active']) && $item['active'] === true) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isParentActive ? 'active' : '' }}">
                                    <i class="nav-icon {{ $item['icon'] }} {{ $item['icon_color'] ?? 'default-sidebar-icon' }}"></i>
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
                                                $childPattern = isset($child['for_active']) ? $child['for_active'] . '*' : $child['route'] . '*';
                                                $isChildActive = request()->routeIs($childPattern);
                                            } catch (\Exception $e) {}
                                        @endphp
                                        <li class="nav-item">
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
                                    $parentPattern = isset($item['for_active']) ? $item['for_active'] . '*' : $item['route'] . '*';
                                    $isActive = request()->routeIs($parentPattern);
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
/* ============================================== */
/* ১. গ্লাস থিম (সাইডবার) বেস স্টাইল */
/* ============================================== */
.sidebar-glass {
    background: rgba(255, 255, 255, 0.05) !important; /* হালকা ট্রান্সপারেন্ট BG */
    backdrop-filter: blur(15px) saturate(150%) !important; /* ব্লার এবং স্যাচুরেশন */
    -webkit-backdrop-filter: blur(15px) saturate(150%) !important;
    border-right: 1px solid rgba(255, 255, 255, 0.1) !important; /* সূক্ষ্ম বর্ডার */
    box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2) !important;
}

/* সাইডবার র‍্যাপার স্বচ্ছ রাখা */
.sidebar-wrapper {
    background: transparent !important;
}
.default-sidebar-icon{
    color: #0f9bd6 !important;
}

/* ============================================== */
/* ২. মেনু আইটেম ডিজাইন */
/* ============================================== */
/* .nav-item {
    margin: 4px 12px !important;
} */

.nav-link {
    border-radius: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    color: #0f9bd6 !important;
    padding: 0.5rem 1rem !important;
    border: 1px solid transparent !important;
}

/* হোভার ইফেক্ট */
.nav-link:hover {
    background: rgba(0, 123, 255, 0.08) !important;
    /* background: rgba(33, 27, 121, 0.1) !important; */
    transform: translateX(2px);
    color: #1a237e !important;
    /* color: #0d47a1 !important; */
}
.nav-link:hover i {
    color: #1a237e !important;
}

/* একটিভ মেনু - Liquid Gradient */
.nav-link.active {
    /* background: linear-gradient(135deg, rgba(15, 155, 214, 0.8), rgba(0, 86, 179, 0.8)) !important; */
    box-shadow: 0 4px 15px rgba(15, 155, 214, 0.4) !important;
    color: #0f2d4a !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}

.nav-link.active i {
    color: #0f2d4a !important;
}

/* সাব-মেনু (Treeview) ডিজাইন */
.nav-treeview {
    background: rgba(255, 255, 255, 0.03) !important;
    margin: 5px 0 5px 0px !important;
    border-left: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 0 0 12px 12px;
    padding: 5px 0;
}

/* ড্রপডাউন ওপেন থাকলে প্যারেন্ট স্টাইল */
.nav-item.menu-open > .nav-link {
    background: rgba(255, 255, 255, 0.07) !important;
    font-weight: 600;
}

/* ============================================== */
/* ৩. অন্যান্য স্টাইল ও ফিক্স */
/* ============================================== */

/* কোম্পানি টেক্সট */
.company-text-sidebar
 {
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    background: linear-gradient(to right, #0f2d4a, #0f9bd6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    padding: 0px 0 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

/* ব্র্যান্ড লোগো গ্লো ইফেক্ট */
aside .brand-image {
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5)) !important;
}
.sidebar-brand .brand-link .brand-image {
    max-height: 33px; /* এক্সপ্যান্ড থাকা অবস্থায় হাইট */
    transition: max-height 0.3s ease-in-out; /* হাইট পরিবর্তনের সময়কাল */
    display: inline-block;
}
.sidebar-brand{
    border-bottom: 1px solid #a0d0ff !important;
    height: 3.56rem !important;
}

/* যখন সাইডবার কোল্যাপস থাকবে */
.sidebar-collapse .sidebar-brand .brand-link .brand-image {
    max-height: 20px; /* কোল্যাপস থাকা অবস্থায় হাইট */
}
.sidebar-collapse .app-sidebar:hover .sidebar-brand .brand-link .brand-image {
    max-height: 33px !important;
}


/* ডিফল্ট অবস্থা */
.company-text-sidebar {
    list-style: none;
    /* padding: 10px; */
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
}

.short-text {
    display: none; /* শুরুতে ছোট নাম হাইড থাকবে */
}

/* যখন সাইডবার কোল্যাপস (Collapse) থাকবে */
.sidebar-collapse .app-sidebar .full-text {
    display: none;
}

.sidebar-collapse .app-sidebar .short-text {
    display: block;
    font-weight: bold;
    transition: all 0.3s ease;
}

/* যখন কোল্যাপস অবস্থায় মাউস হোভার (Hover) করবেন */
.sidebar-collapse .app-sidebar:hover .full-text {
    display: block;
}

.sidebar-collapse .app-sidebar:hover .short-text {
    display: none;
}

/* টেক্সট অ্যানিমেশন স্মুথ করার জন্য */
.full-text, .short-text {
    transition: opacity 0.3s ease-in-out;
}

/* মোবাইল রেসপন্সিভ */
@media (max-width: 767px) {
    .hide-mobile {
        display: none !important;
    }
}
</style>

<style>
    .icc-1  { color: #2c3e50; }
    .icc-2  { color: #34495e; }
    .icc-3  { color: #7f8c8d; }
    .icc-4  { color: #95a5a6; }
    .icc-5  { color: #bdc3c7; }

    .icc-6  { color: #0d6efd; }
    .icc-7  { color: #1a73e8; }
    .icc-8  { color: #2962ff; }
    .icc-9  { color: #3f51b5; }
    .icc-10 { color: #5c6bc0; }

    .icc-11 { color: #4b4dfc; }
    .icc-12 { color: #536dfe; }
    .icc-13 { color: #7986cb; }
    .icc-14 { color: #3d5afe; }
    .icc-15 { color: #1e40af; }

    .icc-16 { color: #20c997; }
    .icc-17 { color: #10b981; }
    .icc-18 { color: #2ecc71; }
    .icc-19 { color: #27ae60; }
    .icc-20 { color: #1abc9c; }

    .icc-21 { color: #0fb9b1; }
    .icc-22 { color: #26de81; }
    .icc-23 { color: #2ed573; }
    .icc-24 { color: #55efc4; }
    .icc-25 { color: #00b894; }

    .icc-26 { color: #f39c12; }
    .icc-27 { color: #f1c40f; }
    .icc-28 { color: #e67e22; }
    .icc-29 { color: #e74c3c; }
    .icc-30 { color: #d35400; }

    .icc-31 { color: #ff7675; }
    .icc-32 { color: #fab1a0; }
    .icc-33 { color: #e17055; }
    .icc-34 { color: #d63031; }
    .icc-35 { color: #ff6b6b; }

    .icc-36 { color: #6c5ce7; }
    .icc-37 { color: #a29bfe; }
    .icc-38 { color: #8e44ad; }
    .icc-39 { color: #9b59b6; }
    .icc-40 { color: #be2edd; }

    .icc-41 { color: #0984e3; }
    .icc-42 { color: #74b9ff; }
    .icc-43 { color: #81ecec; }
    .icc-44 { color: #00cec9; }
    .icc-45 { color: #00a8ff; }

    .icc-46 { color: #6ab04c; }
    .icc-47 { color: #badc58; }
    .icc-48 { color: #7bed9f; }
    .icc-49 { color: #55efc4; }
    .icc-50 { color: #1dd1a1; }

    .icc-51 { color: #d980fa; }
    .icc-52 { color: #e056fd; }
    .icc-53 { color: #b33771; }
    .icc-54 { color: #8b53ff; }
    .icc-55 { color: #9f6bff; }

    .icc-56 { color: #3d3d3d; }
    .icc-57 { color: #4f4f4f; }
    .icc-58 { color: #5f6368; }
    .icc-59 { color: #757575; }
    .icc-60 { color: #8e8e8e; }

    .icc-61 { color: #ff9f1a; }
    .icc-62 { color: #ffa502; }
    .icc-63 { color: #ffb142; }
    .icc-64 { color: #ff7f50; }
    .icc-65 { color: #ff6348; }

    .icc-66 { color: #341f97; }
    .icc-67 { color: #2e86de; }
    .icc-68 { color: #1b9cfc; }
    .icc-69 { color: #3742fa; }
    .icc-70 { color: #5352ed; }

    .icc-71 { color: #2d3436; }
    .icc-72 { color: #636e72; }
    .icc-73 { color: #b2bec3; }
    .icc-74 { color: #dfe6e9; }
    .icc-75 { color: #a4b0be; }

    .icc-76 { color: #0a3d62; }
    .icc-77 { color: #3c6382; }
    .icc-78 { color: #60a3bc; }
    .icc-79 { color: #82ccdd; }
    .icc-80 { color: #0abde3; }

    .icc-81 { color: #30336b; }
    .icc-82 { color: #130f40; }
    .icc-83 { color: #535c68; }
    .icc-84 { color: #95afc0; }
    .icc-85 { color: #7ed6df; }

    .icc-86 { color: #eb4d4b; }
    .icc-87 { color: #ff4757; }
    .icc-88 { color: #ff6b81; }
    .icc-89 { color: #ff4d4d; }
    .icc-90 { color: #ff3838; }

    .icc-91 { color: #374151; }
    .icc-92 { color: #4b5563; }
    .icc-93 { color: #6b7280; }
    .icc-94 { color: #9ca3af; }
    .icc-95 { color: #d1d5db; }

    .icc-96 { color: #2563eb; }
    .icc-97 { color: #1d4ed8; }
    .icc-98 { color: #1e3a8a; }
    .icc-99 { color: #60a5fa; }
    .icc-100{ color: #93c5fd; }
</style>
