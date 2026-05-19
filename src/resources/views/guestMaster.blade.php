<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'ESTIAQUE') }}</title>
    <meta name="title" content="@yield('meta-title', config('me_settings.meta_title'))" />
    <meta name="author" content="@yield('meta-author', config('me_settings.meta_author'))" />
    <meta name="description" content="@yield('meta-description', config('me_settings.meta_description'))" />
    <meta name="keywords" content="@yield('meta-keywords', config('me_settings.meta_keywords'))" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
        --bg-1: #ecf7f4;
        --bg-2: #e8f0ff;
        --bg-3: #fff1de;
        --ink: #1e2d40;
        --muted: #51627c;
        --card: rgba(255, 255, 255, 0.52);
        --card-strong: rgba(255, 255, 255, 0.7);
        --line: rgba(102, 148, 255, 0.2);
        --primary: #1461e2;
        --primary-soft: #5f95ff;
        --success: #2da36a;
        --warning: #c78519;
        --danger: #c9435e;
        --blur: 14px;
        --shadow: 0 14px 45px rgba(49, 80, 139, 0.13);
        }

        body.theme-night {
        --bg-1: #081923;
        --bg-2: #142536;
        --bg-3: #13263f;
        --ink: #deebff;
        --muted: #a4bbd7;
        --card: rgba(17, 40, 61, 0.56);
        --card-strong: rgba(20, 46, 70, 0.74);
        --line: rgba(122, 175, 255, 0.24);
        --primary: #6ab0ff;
        --primary-soft: #9cc8ff;
        --success: #6fd89b;
        --warning: #f2c56a;
        --danger: #ff94a7;
        --shadow: 0 16px 52px rgba(1, 7, 15, 0.4);
        }

        * {
        box-sizing: border-box;
        }

        html {
        scroll-behavior: smooth;
        }

        body {
        margin: 0;
        min-height: 100vh;
        color: var(--ink);
        font-family: "Sora", sans-serif;
        background:
            radial-gradient(circle at 15% 15%, var(--bg-3), transparent 36%),
            radial-gradient(circle at 80% 8%, #d8f7f2, transparent 34%),
            linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 48%, #f8fcff 100%);
        overflow-x: hidden;
        transition: background 0.35s ease, color 0.35s ease;
        }

        .ambient {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
        }

        .orb {
        position: absolute;
        border-radius: 999px;
        opacity: 0.24;
        filter: blur(2px);
        animation: drift 18s ease-in-out infinite alternate;
        }

        .orb.one {
        width: 240px;
        height: 240px;
        left: -60px;
        top: 7%;
        background: linear-gradient(45deg, #71ffd2, #82a6ff);
        }

        .orb.two {
        width: 210px;
        height: 210px;
        right: -40px;
        top: 38%;
        animation-delay: 0.3s;
        background: linear-gradient(45deg, #ffd17d, #ffa3b0);
        }

        .orb.three {
        width: 180px;
        height: 180px;
        left: 42%;
        bottom: -40px;
        animation-delay: 0.9s;
        background: linear-gradient(45deg, #89dbff, #9ef2cd);
        }

        @keyframes drift {
        from {
            transform: translateY(0) translateX(0) scale(1);
        }
        to {
            transform: translateY(-40px) translateX(18px) scale(1.08);
        }
        }

        .app-wrap {
        position: relative;
        z-index: 1;
        padding-bottom: 5.2rem;
        min-height: 99.8vh;        }

        .glass {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 1.15rem;
        backdrop-filter: blur(var(--blur)) saturate(140%);
        box-shadow: var(--shadow);
        transition: background 0.25s ease, border 0.25s ease, box-shadow 0.25s ease;
        }

        .hero {
        padding: 1.4rem;
        margin-top: 1.05rem;
        margin-bottom: 1.5rem;
        display: grid;
        gap: 1rem;
        grid-template-columns: 1fr auto;
        align-items: center;
        }

        .hero h1 {
        margin: 0;
        font-family: "Space Grotesk", sans-serif;
        font-weight: 700;
        letter-spacing: -0.03em;
        font-size: clamp(1.35rem, 2vw, 2rem);
        }

        .hero p {
        margin: 0.65rem 0 0;
        color: var(--muted);
        font-size: 0.95rem;
        }

        .hero-meta {
        text-align: right;
        }

        .hero-meta .clock {
        display: block;
        font-family: "Space Grotesk", sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        }

        .hero-meta .date {
        color: var(--muted);
        font-size: 0.84rem;
        }

        .section {
        margin-bottom: 1.5rem;
        }

        .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.65rem;
        font-weight: 700;
        font-family: "Space Grotesk", sans-serif;
        letter-spacing: 0.01em;
        }

        .section-title i {
        color: var(--primary);
        }

        .section-bar {
        width: 78px;
        height: 4px;
        border-radius: 999px;
        margin-bottom: 0.95rem;
        background: linear-gradient(90deg, var(--primary), #4ad1a4);
        opacity: 0.58;
        }

        .js-panel {
        padding: 1rem;
        border-radius: 1rem;
        }

        .js-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
        }

        .js-status {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.7rem;
        }

        .js-chip {
        border-radius: 999px;
        padding: 0.23rem 0.62rem;
        font-size: 0.78rem;
        border: 1px solid var(--line);
        background: var(--card-strong);
        color: var(--ink);
        }

        .js-log {
        margin: 0;
        padding: 0.45rem 0.65rem;
        list-style: none;
        border-radius: 0.75rem;
        background: var(--card-strong);
        border: 1px solid var(--line);
        max-height: 140px;
        overflow: auto;
        font-size: 0.82rem;
        color: var(--muted);
        }

        .js-log li {
        padding: 0.2rem 0;
        border-bottom: 1px dashed rgba(96, 136, 214, 0.18);
        }

        .js-log li:last-child {
        border-bottom: none;
        }

        .btn-glass,
        .btn-glass.btn {
        border-radius: 0.85rem;
        border: 1px solid var(--line);
        background: var(--card-strong);
        color: var(--ink);
        backdrop-filter: blur(7px) saturate(150%);
        transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn-glass:hover,
        .btn-glass:focus-visible {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(57, 103, 194, 0.18);
        color: var(--ink);
        }

        .btn-glass:focus-visible {
        outline: 2px solid rgba(40, 110, 230, 0.34);
        outline-offset: 1px;
        }

        .btn-close.btn-close-white {
        filter: none;
        }

        .theme-btn {
        min-width: 140px;
        }

        .card,
        .card-header,
        .card-footer {
        border: 0;
        background: transparent;
        }

        .card .card-title {
        color: var(--ink);
        }

        .card .card-text {
        color: var(--ink);
        }

        .card.glass .card-body {
        padding: 1.1rem;
        }

        .metric-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.8rem;
        margin-top: 0.9rem;
        }

        .metric {
        border-radius: 0.9rem;
        padding: 0.75rem;
        background: var(--card-strong);
        border: 1px solid var(--line);
        text-align: center;
        }

        .metric .n {
        font-family: "Space Grotesk", sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        }

        .metric .t {
        color: var(--muted);
        font-size: 0.79rem;
        }

        .form-control,
        .form-select,
        textarea {
        background: var(--card-strong);
        border: 1px solid var(--line);
        border-radius: 0.8rem;
        color: var(--ink);
        font-size: 16px;
        }

        .form-control:focus,
        .form-select:focus {
        background: var(--card-strong);
        border-color: rgba(53, 126, 255, 0.45);
        box-shadow: 0 0 0 0.2rem rgba(88, 150, 255, 0.2);
        color: var(--ink);
        }

        .table-wrap {
        padding: 0.9rem;
        overflow-x: auto;
        }

        .table.glass-table {
        margin: 0;
        --bs-table-bg: transparent;
        --bs-table-color: var(--ink);
        --bs-table-striped-bg: rgba(56, 103, 190, 0.06);
        --bs-table-hover-bg: rgba(56, 103, 190, 0.13);
        --bs-table-border-color: rgba(90, 140, 230, 0.18);
        }

        .table.glass-table thead th {
        color: var(--primary);
        font-family: "Space Grotesk", sans-serif;
        font-weight: 700;
        white-space: nowrap;
        }

        .status-pill {
        border-radius: 999px;
        padding: 0.24rem 0.55rem;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid transparent;
        }

        .status-pill.active {
        color: #0b6f46;
        background: rgba(95, 221, 157, 0.2);
        border-color: rgba(95, 221, 157, 0.4);
        }

        .status-pill.pending {
        color: #986107;
        background: rgba(241, 195, 98, 0.22);
        border-color: rgba(241, 195, 98, 0.45);
        }

        .status-pill.blocked {
        color: #a63958;
        background: rgba(245, 135, 164, 0.19);
        border-color: rgba(245, 135, 164, 0.4);
        }

        .alert {
        border-radius: 0.9rem;
        border: 1px solid var(--line);
        background: var(--card-strong);
        color: var(--ink);
        }

        .alert-glass-success {
        background: rgba(213, 255, 217, 0.42) !important;
        color: #25ad64;
        border: 1.2px solid rgba(43, 230, 84, 0.12);
        }

        .alert-glass-danger {
        background: rgba(255, 230, 232, 0.38) !important;
        color: #cc2857;
        border: 1.2px solid rgba(245, 43, 70, 0.13);
        }

        .alert-glass-info {
        background: rgba(195, 236, 255, 0.37) !important;
        color: #14798a;
        border: 1.2px solid rgba(43, 181, 222, 0.1);
        }

        .modal-content {
        border-radius: 1.1rem;
        background: rgba(250, 252, 255, 0.5) !important;
        box-shadow: 0 4px 32px 0 rgba(101, 116, 255, 0.17);
        backdrop-filter: blur(12px) saturate(160%);
        border: 1.7px solid rgba(140, 194, 255, 0.14);
        }

        .modal-dialog.modal-dialog-top {
        margin: 0.8rem auto 0;
        }

        .modal {
        --bs-modal-margin: 0.8rem;
        }

        .modal-backdrop.show {
        opacity: 0;
        }

        .modal-header,
        .modal-footer {
        background: transparent !important;
        border: none;
        }

        .toastr {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        min-width: 220px;
        z-index: 2200;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.28s;
        }

        .toastr.show {
        opacity: 1;
        pointer-events: auto;
        }

        .toast-glass {
        margin-bottom: 0.55rem;
        padding: 0.8rem 0.85rem;
        background: rgba(250, 252, 255, 0.65) !important;
        border: 1px solid var(--line);
        border-radius: 1.1rem;
        box-shadow: 0 2.5px 20px rgba(105, 145, 230, 0.09);
        color: #345ec8;
        }

        .toast-glass.toast-glass-info {
        background: rgba(195, 236, 255, 0.37) !important;
        color: #14798a;
        border: 1.2px solid rgba(43, 181, 222, 0.1);
        }

        .toast-glass.toast-glass-success {
        background: rgba(213, 255, 217, 0.42) !important;
        color: #25ad64;
        border: 1.2px solid rgba(43, 230, 84, 0.12);
        }

        .toast-glass.toast-glass-danger {
        background: rgba(255, 230, 232, 0.38) !important;
        color: #cc2857;
        border: 1.2px solid rgba(245, 43, 70, 0.13);
        }

        .toast-glass.toast-glass-warning {
        background: rgba(255, 244, 220, 0.42) !important;
        color: #b27512;
        border: 1.2px solid rgba(232, 173, 54, 0.16);
        }

        .floating-menu-wrap {
        position: fixed;
        right: 0.9rem;
        bottom: calc(0.7rem + env(safe-area-inset-bottom));
        z-index: 1200;
        }

        .fab-logo-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 1px solid rgba(116, 167, 255, 0.45);
        background: linear-gradient(135deg, rgba(101, 189, 255, 0.8), rgba(79, 124, 239, 0.85));
        color: #f5fbff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        box-shadow: 0 12px 30px rgba(37, 86, 173, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .fab-logo-btn:hover,
        .fab-logo-btn:focus-visible {
        transform: scale(1.06);
        color: #ffffff;
        box-shadow: 0 14px 34px rgba(30, 86, 189, 0.46);
        }

        .radial-menu {
        position: absolute;
        right: 30px;
        bottom: 95px;
        width: 1px;
        height: 1px;
        pointer-events: none;
        }

        .radial-menu::before {
        content: "";
        position: absolute;
        width: 168px;
        height: 168px;
        right: -132px;
        bottom: -132px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(103, 179, 255, 0.24) 0%, rgba(103, 179, 255, 0) 70%);
        opacity: 0;
        transform: scale(0.88);
        transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .radial-menu.open::before {
        opacity: 1;
        transform: scale(1);
        }

        .radial-action {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 46px;
        height: 46px;
        opacity: 0;
        border-radius: 50%;
        border: 1px solid rgba(115, 166, 255, 0.32);
        background: rgba(237, 248, 255, 0.94);
        color: #1e5dcf;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        box-shadow: 0 10px 24px rgba(47, 101, 190, 0.28);
        transform: translate(calc(-50%), calc(-50%)) scale(0.5);
        transition: transform 0.32s cubic-bezier(0.2, 0.9, 0.25, 1.04), opacity 0.22s ease;
        }

        .radial-menu.open .radial-action {
        opacity: 1;
        pointer-events: auto;
        transform: translate(calc(-50% + var(--tx, 0)), calc(-50% + var(--ty, 0))) scale(1);
        transition-delay: var(--d, 0ms);
        }

        .radial-action::after {
        content: attr(data-label);
        position: absolute;
        right: 115%;
        top: 50%;
        transform: translateY(-50%) scale(0.95);
        transform-origin: right center;
        white-space: nowrap;
        font-size: 0.74rem;
        padding: 0.16rem 0.46rem;
        border-radius: 999px;
        color: #1c4066;
        border: 1px solid rgba(116, 166, 242, 0.3);
        background: rgba(244, 251, 255, 0.93);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.18s ease, transform 0.18s ease;
        }

        .radial-action:hover::after,
        .radial-action:focus-visible::after {
        opacity: 1;
        transform: translateY(-50%) scale(1);
        }

        .radial-action .radial-label {
        display: none;
        }

        .footer-glass {
        /* position: fixed; */
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1000;
        padding: 0.45rem 1rem;
        font-size: 0.88rem;
        text-align: center;
        background: var(--card-strong);
        border-top: 1px solid var(--line);
        backdrop-filter: blur(10px);
        color: var(--muted);
        }

        .fade-in-up {
        opacity: 0;
        transform: translateY(10px);
        animation: fade-in-up 0.5s ease forwards;
        }

        .fade-delay-1 {
        animation-delay: 0.05s;
        }

        .fade-delay-2 {
        animation-delay: 0.12s;
        }

        .fade-delay-3 {
        animation-delay: 0.18s;
        }

        @keyframes fade-in-up {
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }

        @media (max-width: 767.98px) {
        .hero {
            grid-template-columns: 1fr;
        }

        .hero-meta {
            text-align: left;
        }

        .metric-grid {
            grid-template-columns: 1fr;
        }

        .floating-menu-wrap {
            right: 0.55rem;
            bottom: calc(0.7rem + env(safe-area-inset-bottom));
        }

        .fab-logo-btn {
            width: 54px;
            height: 54px;
            font-size: 1.35rem;
        }

        .radial-menu {
            right: 27px;
            bottom: 90px;
        }

        .radial-action {
            width: 40px;
            height: 40px;
        }

        .radial-action::after {
            display: none;
        }

        .modal-dialog.modal-dialog-top {
            margin-top: 0.7rem;
        }
        }

        @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation: none !important;
            transition: none !important;
        }

        html {
            scroll-behavior: auto;
        }
        }
        .btn-section{
            text-align: end;
        }
        [class^="btn-encodex"],
        [class*=" btn-encodex"] {
            border-radius: 5px;
            padding: 5px 10px;
            border:1px solid whitesmoke
        }

        .controls{width: 100%;}
    </style>
    @stack('css')
</head>
<body>
    <div class="ambient" aria-hidden="true">
        <span class="orb one"></span>
        <span class="orb two"></span>
        <span class="orb three"></span>
    </div>

  <main class="app-wrap">
    <div class="btn-section">
        @stack('buttons')
    </div>

    @yield('content')
  </main>

  <div class="floating-menu-wrap" id="floatingMenuRoot">
    <div class="radial-menu" id="radialMenu"></div>
    <button class="fab-logo-btn" id="fabLogoBtn" title="Open Menu" aria-label="Open Floating Menu">
      {{-- <i class="bi bi-lightning-charge"></i> --}}
      @include('me::svg3')
    </button>
  </div>

  <footer class="footer-glass" id="footer">
    M. ESTIAQUE &copy; @php echo date('Y'); @endphp
  </footer>

  @php
    $menuConfig = config('guestSidebar.menu', []);
  @endphp

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const menuConfig = @json($menuConfig);

    const body = document.body;
    const liveClock = document.getElementById("liveClock");
    const liveDate = document.getElementById("liveDate");
    const themeToggle = document.getElementById("themeToggle");
    const metricMode = document.getElementById("metricMode");
    const tableBody = document.getElementById("scoreTableBody");
    const metricRows = document.getElementById("metricRows");
    const metricCards = document.getElementById("metricCards");
    const tableFilter = document.getElementById("tableFilter");
    const jsActionButtons = document.querySelectorAll("[data-js-action]");
    const jsThemeState = document.getElementById("jsThemeState");
    const jsRowsState = document.getElementById("jsRowsState");
    const jsMenuState = document.getElementById("jsMenuState");
    const jsLastAction = document.getElementById("jsLastAction");
    const jsActionLog = document.getElementById("jsActionLog");

    const fabLogoBtn = document.getElementById("fabLogoBtn");
    const radialMenu = document.getElementById("radialMenu");
    const openModalBtn = document.getElementById("openModalBtn");
    const modalEl = document.getElementById("glassModalDemo");
    let menuOpen = false;
    let closeMenuTimer = null;
    let glassModalInstance = null;

    function updateClock() {
      const now = new Date();
      if (liveClock) {
        liveClock.textContent = now.toLocaleTimeString();
      }
      if (liveDate) {
        liveDate.textContent = now.toLocaleDateString(undefined, {
          weekday: "short",
          month: "short",
          day: "numeric",
          year: "numeric"
        });
      }
    }

    function setTheme(isNight) {
      body.classList.toggle("theme-night", isNight);
      if (metricMode) {
        metricMode.textContent = isNight ? "Night" : "Day";
      }
      if (themeToggle) {
        themeToggle.innerHTML = isNight
          ? '<i class="bi bi-sun me-1"></i> Day Mode'
          : '<i class="bi bi-moon-stars me-1"></i> Night Mode';
      }
      localStorage.setItem("guest-theme", isNight ? "night" : "day");
      syncJsStatus();
    }

    function initTheme() {
      const saved = localStorage.getItem("guest-theme");
      setTheme(saved === "night");
    }

    function showToastr(head, msg, ms, type) {
      const root = document.getElementById("toastr-root");
      const ttl = typeof ms === "number" ? ms : 1900;
      const tone = String(type || "info").toLowerCase();
      const toneClass = {
        info: "toast-glass-info",
        success: "toast-glass-success",
        error: "toast-glass-danger",
        danger: "toast-glass-danger",
        warning: "toast-glass-warning"
      }[tone];
      const item = document.createElement("div");
      item.className = "toast-glass" + (toneClass ? " " + toneClass : "");
      item.innerHTML = '<strong>' + head + '</strong>' + (msg ? '<div class="small mt-1">' + msg + "</div>" : "");
      root.appendChild(item);
      root.classList.add("show");

      setTimeout(function () {
        item.style.opacity = "0";
        item.style.transform = "translateY(-8px)";
      }, Math.max(0, ttl - 180));

      setTimeout(function () {
        item.classList.remove("show");
        if (item.parentNode) {
          item.parentNode.removeChild(item);
        }
      }, ttl + 40);

      setTimeout(function () {
        if (root.children.length === 0) {
          root.classList.remove("show");
        }
      }, ttl + 140);

      setLastAction("Toast shown");
    }

    function setLastAction(text) {
      if (jsLastAction) {
        jsLastAction.textContent = text;
      }
      if (jsActionLog) {
        const item = document.createElement("li");
        item.textContent = new Date().toLocaleTimeString() + " - " + text;
        jsActionLog.prepend(item);
        while (jsActionLog.children.length > 6) {
          jsActionLog.removeChild(jsActionLog.lastElementChild);
        }
      }
    }

    function syncJsStatus() {
      if (jsThemeState && metricMode) {
        jsThemeState.textContent = metricMode.textContent;
      }
      if (jsRowsState && metricRows) {
        jsRowsState.textContent = metricRows.textContent;
      }
      if (jsMenuState) {
        jsMenuState.textContent = menuOpen ? "Open" : "Closed";
      }
    }

    function buildRadialMenu() {
      radialMenu.innerHTML = "";
      const isMobile = window.innerWidth < 600;
      const btnSize = isMobile ? 40 : 46;
      const spacing = isMobile ? 12 : 14;
      const itemCount = menuConfig.length;

      menuConfig.forEach(function (item, idx) {
        // Vertical positioning: items appear from bottom going upward
        const x = 0;
        const y = -(idx * (btnSize + spacing));

        const el = document.createElement("a");
        el.className = "radial-action";
        el.href = item.href;
        el.title = item.label;
        el.dataset.label = item.label;
        el.setAttribute("aria-label", item.label);
        el.innerHTML = '<i class="bi ' + item.icon + '"></i><span class="radial-label">' + item.label + "</span>";
        el.style.setProperty("--tx", x + "px");
        el.style.setProperty("--ty", y + "px");
        el.style.setProperty("--d", (idx * 20) + "ms");
        el.tabIndex = menuOpen ? 0 : -1;
        el.addEventListener("click", closeMenu);
        radialMenu.appendChild(el);
      });

      radialMenu.classList.toggle("open", menuOpen);
      fabLogoBtn.setAttribute("aria-expanded", String(menuOpen));
    }

    function closeMenuOnDocumentClick(event) {
      if (!fabLogoBtn.contains(event.target) && !radialMenu.contains(event.target)) {
        closeMenu();
      }
    }

    function openMenu() {
      menuOpen = true;
      buildRadialMenu();
      document.addEventListener("mousedown", closeMenuOnDocumentClick);
      clearTimeout(closeMenuTimer);
      // closeMenuTimer = setTimeout(closeMenu, 4500);
      syncJsStatus();
      setLastAction("Floating menu opened");
    }

    function closeMenu() {
      menuOpen = false;
      buildRadialMenu();
      document.removeEventListener("mousedown", closeMenuOnDocumentClick);
      clearTimeout(closeMenuTimer);
      syncJsStatus();
    }

    function filterTableRows(keyword) {
      if (!tableBody || !metricRows) {
        return;
      }

      const term = keyword.trim().toLowerCase();
      let visibleCount = 0;

      tableBody.querySelectorAll("tr").forEach(function (row) {
        const text = row.textContent.toLowerCase();
        const show = term === "" || text.indexOf(term) !== -1;
        row.style.display = show ? "" : "none";
        if (show) {
          visibleCount += 1;
        }
      });

      metricRows.textContent = String(visibleCount);
      syncJsStatus();
    }

    function updateMetrics() {
      if (!metricCards || !metricRows || !tableBody) {
        return;
      }

      metricCards.textContent = String(document.querySelectorAll("#section-cards .card").length);
      metricRows.textContent = String(tableBody.querySelectorAll("tr").length);
      syncJsStatus();
    }

    function runJsAction(action) {
      if (action === "toggle-theme") {
        setTheme(!body.classList.contains("theme-night"));
        setLastAction("Theme toggled");
        return;
      }

      if (action === "open-modal") {
        if (glassModalInstance) {
          glassModalInstance.show();
          setLastAction("Modal opened");
        }
        return;
      }

      if (action === "show-toast") {
        showToastr("JS Action", "Toastr triggered from JS panel.", 1900, "info");
        return;
      }

      if (action === "filter-pending") {
        tableFilter.value = "pending";
        filterTableRows("pending");
        setLastAction("Table filtered by pending");
        return;
      }

      if (action === "clear-filter") {
        tableFilter.value = "";
        filterTableRows("");
        setLastAction("Table filter cleared");
        return;
      }

      if (action === "toggle-menu") {
        if (menuOpen) {
          closeMenu();
          setLastAction("Floating menu closed");
        } else {
          openMenu();
        }
      }
    }

    updateClock();
    if (liveClock || liveDate) {
      setInterval(updateClock, 1000);
    }

    initTheme();
    updateMetrics();
    buildRadialMenu();
    syncJsStatus();

    if (modalEl && window.bootstrap && window.bootstrap.Modal) {
      glassModalInstance = new window.bootstrap.Modal(modalEl);
    }

    if (themeToggle) {
      themeToggle.addEventListener("click", function () {
        setTheme(!body.classList.contains("theme-night"));
        const modeLabel = metricMode ? metricMode.textContent : (body.classList.contains("theme-night") ? "Night" : "Day");
        showToastr("Theme updated", "Switched to " + modeLabel + " mode", 1900, "info");
      });
    }

    fabLogoBtn.addEventListener("click", function (e) {
      e.preventDefault();
      if (menuOpen) {
        console.log("Closing menu from FAB click");
        closeMenu();
      } else {
        openMenu();
      }
    });

    window.addEventListener("resize", buildRadialMenu);

    jsActionButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        runJsAction(btn.dataset.jsAction || "");
      });
    });

    if (openModalBtn && glassModalInstance) {
      openModalBtn.addEventListener("click", function (e) {
        e.preventDefault();
        glassModalInstance.show();
      });
    }

    if (tableFilter) {
      tableFilter.addEventListener("input", function (e) {
        filterTableRows(e.target.value);
      });
    }

    const demoForm = document.getElementById("demoForm");
    if (demoForm) {
      demoForm.addEventListener("submit", function (e) {
        e.preventDefault();
        showToastr("Form submitted", "Input captured and reset complete.", 1900, "success");
        e.target.reset();
      });
    }

    document.addEventListener("shown.bs.modal", function (event) {
      const firstInput = event.target.querySelector("input,textarea,select");
      if (firstInput) {
        setTimeout(function () {
          firstInput.focus();
        }, 120);
      }
    });
  </script>
  @php
    $flashToasts = [];
    $flashKeys = session()->get('_flash.new', []);
    $commonViewToastKeys = ['success', 'error', 'info', 'warning', 'message'];

    foreach ($flashKeys as $flashKey) {
      if ($flashKey === '_old_input') {
        continue;
      }

      $flashValue = session($flashKey);

      if (is_string($flashValue) && trim($flashValue) !== '') {
        $flashToasts[] = [
          'type' => $flashKey,
          'message' => $flashValue,
        ];
      }
    }

    foreach ($commonViewToastKeys as $viewToastKey) {
      $viewToastValue = ${$viewToastKey} ?? null;

      if (is_string($viewToastValue) && trim($viewToastValue) !== '') {
        $alreadyExists = collect($flashToasts)->contains(function ($toast) use ($viewToastKey, $viewToastValue) {
          return $toast['type'] === $viewToastKey && $toast['message'] === $viewToastValue;
        });

        if (!$alreadyExists) {
          $flashToasts[] = [
            'type' => $viewToastKey,
            'message' => $viewToastValue,
          ];
        }
      }
    }

    if (isset($errors) && $errors->any()) {
      $flashToasts[] = [
        'type' => 'error',
        'message' => $errors->first(),
      ];
    }
  @endphp
  <script>
    (function () {
      const flashToasts = @json($flashToasts);
      const titleMap = {
        success: "Success",
        error: "Error",
        info: "Info",
        warning: "Warning"
      };

      if (!Array.isArray(flashToasts) || flashToasts.length === 0 || typeof showToastr !== "function") {
        return;
      }

      flashToasts.forEach(function (toast, index) {
        const type = String(toast.type || "info").toLowerCase();
        const message = String(toast.message || "").trim();

        if (!message) {
          return;
        }

        const title = titleMap[type] || type.charAt(0).toUpperCase() + type.slice(1);
        setTimeout(function () {
          showToastr(title, message, 2600, type);
        }, index * 220);
      });
    })();
  </script>
  @stack('js')
</body>
</html>
