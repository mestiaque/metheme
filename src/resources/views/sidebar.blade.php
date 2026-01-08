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

/* ============================================== */
/* ২. মেনু আইটেম ডিজাইন */
/* ============================================== */
/* .nav-item {
    margin: 4px 12px !important;
} */

.nav-link {
    border-radius: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    color: rgba(160, 16, 16, 0.9) !important;
    padding: 0.5rem 1rem !important;
    border: 1px solid transparent !important;
}

/* হোভার ইফেক্ট */
.nav-link:hover {
    background: rgba(33, 27, 121, 0.1) !important;
    transform: translateX(2px);
}

/* একটিভ মেনু - Liquid Gradient */
.nav-link.active {
    background: linear-gradient(135deg, rgba(15, 155, 214, 0.8), rgba(0, 86, 179, 0.8)) !important;
    box-shadow: 0 4px 15px rgba(15, 155, 214, 0.4) !important;
    color: #025e0e !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}

.nav-link.active i {
    color: rgb(38, 207, 4) !important;
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
    background: linear-gradient(to right, #ff7979, #ffe26e);
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

