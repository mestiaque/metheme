<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>guestMaster - Dynamic Glass UI</title>
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
        }

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

        .floating-menu-wrap {
        position: fixed;
        right: 0.9rem;
        bottom: calc(3.2rem + env(safe-area-inset-bottom));
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
        bottom: 30px;
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
        top: 0;
        left: 0;
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
        transform: translate(0, 0) scale(0.5);
        transition: transform 0.32s cubic-bezier(0.2, 0.9, 0.25, 1.04), opacity 0.22s ease;
        }

        .radial-menu.open .radial-action {
        opacity: 1;
        pointer-events: auto;
        transform: translate(var(--tx, 0), var(--ty, 0)) scale(1);
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
        position: fixed;
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
            bottom: calc(5.35rem + env(safe-area-inset-bottom));
        }

        .fab-logo-btn {
            width: 54px;
            height: 54px;
            font-size: 1.35rem;
        }

        .radial-menu {
            right: 27px;
            bottom: 27px;
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
    </style>
</head>
<body>
    <div class="ambient" aria-hidden="true">
        <span class="orb one"></span>
        <span class="orb two"></span>
        <span class="orb three"></span>
    </div>

  <main class="app-wrap">
    <div class="container py-3 py-md-4">
      <section class="hero glass fade-in-up" aria-label="Hero section">
        <div>
          <h1>guestMaster UI Playground</h1>
          <p>Cleaner style, smoother interactions, and fixed glitches for a more premium demo experience.</p>
          <div class="metric-grid">
            <div class="metric">
              <div class="n" id="metricCards">3</div>
              <div class="t">Cards</div>
            </div>
            <div class="metric">
              <div class="n" id="metricRows">3</div>
              <div class="t">Rows</div>
            </div>
            <div class="metric">
              <div class="n" id="metricMode">Day</div>
              <div class="t">Theme</div>
            </div>
          </div>
        </div>
        <div class="hero-meta">
          <span id="liveClock" class="clock">--:--:--</span>
          <span id="liveDate" class="date">Loading date...</span>
          <button id="themeToggle" class="btn btn-glass theme-btn mt-2" type="button" aria-label="Toggle color theme">
            <i class="bi bi-moon-stars me-1"></i> Night Mode
          </button>
        </div>
      </section>

      <section class="section fade-in-up fade-delay-1" id="section-buttons">
        <div class="section-title"><i class="bi bi-sliders"></i>Buttons and States</div>
        <div class="section-bar"></div>
        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-glass" type="button">Primary</button>
          <button class="btn btn-glass" type="button">Success</button>
          <button class="btn btn-glass" type="button">Warning</button>
          <button class="btn btn-glass" type="button">Danger</button>
          <button class="btn btn-glass" type="button">Info</button>
          <button class="btn btn-glass" type="button" disabled>Disabled</button>
        </div>
      </section>

      <section class="section fade-in-up fade-delay-1" id="section-js-tools">
        <div class="section-title"><i class="bi bi-cpu"></i>JS Actions Panel</div>
        <div class="section-bar"></div>
        <div class="glass js-panel">
          <div class="js-actions">
            <button type="button" class="btn btn-glass btn-sm" data-js-action="toggle-theme">Toggle Theme</button>
            <button type="button" class="btn btn-glass btn-sm" data-js-action="open-modal">Open Modal</button>
            <button type="button" class="btn btn-glass btn-sm" data-js-action="show-toast">Show Toast</button>
            <button type="button" class="btn btn-glass btn-sm" data-js-action="filter-pending">Filter Pending</button>
            <button type="button" class="btn btn-glass btn-sm" data-js-action="clear-filter">Clear Filter</button>
            <button type="button" class="btn btn-glass btn-sm" data-js-action="toggle-menu">Toggle Menu</button>
          </div>

          <div class="js-status">
            <span class="js-chip">Theme: <strong id="jsThemeState">Day</strong></span>
            <span class="js-chip">Rows: <strong id="jsRowsState">3</strong></span>
            <span class="js-chip">Menu: <strong id="jsMenuState">Closed</strong></span>
            <span class="js-chip">Last: <strong id="jsLastAction">Ready</strong></span>
          </div>

          <ul id="jsActionLog" class="js-log">
            <li>JS panel initialized.</li>
          </ul>
        </div>
      </section>

      <section class="section fade-in-up fade-delay-1" id="section-form">
        <div class="section-title"><i class="bi bi-ui-checks-grid"></i>Interactive Form</div>
        <div class="section-bar"></div>
        <form id="demoForm" class="row gy-2 gx-3 align-items-end p-3 glass" autocomplete="off">
          <div class="col-lg-4 col-12">
            <label for="demoInput" class="form-label">Name</label>
            <input id="demoInput" type="text" class="form-control" placeholder="John Doe" required>
          </div>
          <div class="col-lg-4 col-12">
            <label for="demoSelect" class="form-label">Department</label>
            <select id="demoSelect" class="form-select" required>
              <option value="">Choose...</option>
              <option>IT</option>
              <option>Design</option>
              <option>Marketing</option>
            </select>
          </div>
          <div class="col-lg-4 col-12">
            <label for="demoTextarea" class="form-label">Comments</label>
            <textarea id="demoTextarea" class="form-control" rows="1" placeholder="Your notes"></textarea>
          </div>
          <div class="col-12 d-flex gap-2 pt-1">
            <button type="submit" class="btn btn-glass">Submit</button>
            <button type="reset" class="btn btn-glass">Reset</button>
          </div>
        </form>
      </section>

      <section class="section fade-in-up fade-delay-2" id="section-cards">
        <div class="section-title"><i class="bi bi-collection"></i>Feature Cards</div>
        <div class="section-bar"></div>
        <div class="row g-3">
          <div class="col-lg-4 col-md-6 col-12">
            <article class="card glass h-100">
              <div class="card-body">
                <h5 class="card-title">Welcome Guest</h5>
                <p class="card-text">A polished glassmorphism base ready for your dashboard pages.</p>
                <a href="#section-buttons" class="btn btn-glass btn-sm">Try Buttons</a>
              </div>
            </article>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <article class="card glass h-100">
              <div class="card-body">
                <h5 class="card-title">Smooth Responsive</h5>
                <p class="card-text">Optimized spacing and stronger contrast for mobile and desktop.</p>
                <a href="#section-form" class="btn btn-glass btn-sm">Form Demo</a>
              </div>
            </article>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <article class="card glass h-100">
              <div class="card-body">
                <h5 class="card-title">Action Menu</h5>
                <p class="card-text">Use the floating action button to jump across all sections instantly.</p>
                <a href="#footer" class="btn btn-glass btn-sm">View Footer</a>
              </div>
            </article>
          </div>
        </div>
      </section>

      <section class="section fade-in-up fade-delay-2" id="section-tables">
        <div class="section-title"><i class="bi bi-table"></i>Table with Live Filter</div>
        <div class="section-bar"></div>
        <div class="glass table-wrap">
          <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <label for="tableFilter" class="mb-0 small text-muted">Search by name or status</label>
            <input id="tableFilter" class="form-control form-control-sm" style="max-width: 240px;" type="text" placeholder="Type to filter...">
          </div>
          <table class="table glass-table table-striped table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th>Score</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="scoreTableBody">
              <tr>
                <td>1</td>
                <td>Jane</td>
                <td><span class="status-pill active">Active</span></td>
                <td>91</td>
                <td><button class="btn btn-glass btn-sm" type="button">Message</button></td>
              </tr>
              <tr>
                <td>2</td>
                <td>Alex</td>
                <td><span class="status-pill pending">Pending</span></td>
                <td>63</td>
                <td><button class="btn btn-glass btn-sm" type="button">Notify</button></td>
              </tr>
              <tr>
                <td>3</td>
                <td>Chris</td>
                <td><span class="status-pill blocked">Blocked</span></td>
                <td>39</td>
                <td><button class="btn btn-glass btn-sm" type="button">Remove</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section class="section fade-in-up fade-delay-3" id="section-modal">
        <div class="section-title"><i class="bi bi-window-sidebar"></i>Modal Demo</div>
        <div class="section-bar"></div>
        <button id="openModalBtn" type="button" class="btn btn-glass" data-bs-toggle="modal" data-bs-target="#glassModalDemo">
          Open Modal
        </button>
      </section>

      <section class="section fade-in-up fade-delay-3" id="section-alerts">
        <div class="section-title"><i class="bi bi-exclamation-octagon"></i>Alerts and Toastr</div>
        <div class="section-bar"></div>
        <div class="alert alert-glass-info d-flex align-items-center mb-2" role="alert">
          <i class="bi bi-info-circle me-2"></i>
          This is a glass info alert - UI is modern and attractive.
        </div>
        <div class="alert alert-glass-success d-flex align-items-center mb-2" role="alert">
          <i class="bi bi-check2-all me-2"></i>
          Glassmorphism looks great for notification states.
        </div>
        <div class="alert alert-glass-danger d-flex align-items-center" role="alert">
          <i class="bi bi-x-circle me-2"></i>
          Even error messages feel user-friendly.
        </div>
        <button class="btn btn-glass mt-2" type="button" onclick="showToastr('Welcome to guestMaster!', 'Explore responsive glass UI :)')">Show Toastr</button>
      </section>
    </div>
  </main>

  <div class="modal fade" id="glassModalDemo" tabindex="-1" aria-labelledby="glassModalDemoLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-top">
      <div class="modal-content glass">
        <div class="modal-header">
          <h5 class="modal-title" id="glassModalDemoLabel"><i class="bi bi-stars"></i> Glassmorphism Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>This modal uses a blurred glass background with Bootstrap 5 and vanilla CSS.</p>
          <p style="font-size: .96rem; color: #748dee;">You can style any modal this way by overriding the <code>.modal-content</code> class.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>

  <div id="toastr-root" class="toastr" aria-live="polite" aria-atomic="true"></div>

  <div class="floating-menu-wrap" id="floatingMenuRoot">
    <div class="radial-menu" id="radialMenu"></div>
    <button class="fab-logo-btn" id="fabLogoBtn" title="Open Menu" aria-label="Open Floating Menu">
      <i class="bi bi-lightning-charge"></i>
    </button>
  </div>

  <footer class="footer-glass" id="footer">
    guestMaster &copy; 2026 | Refined glass UI with Bootstrap 5
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const menuConfig = [
      { icon: "bi-house", href: "#section-cards", label: "Cards" },
      { icon: "bi-ui-checks", href: "#section-buttons", label: "Buttons" },
      { icon: "bi-person", href: "#section-form", label: "Form" },
      { icon: "bi-table", href: "#section-tables", label: "Table" },
      { icon: "bi-star", href: "#section-modal", label: "Modal" },
      { icon: "bi-bell", href: "#section-alerts", label: "Alerts" }
    ];

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
      liveClock.textContent = now.toLocaleTimeString();
      liveDate.textContent = now.toLocaleDateString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric"
      });
    }

    function setTheme(isNight) {
      body.classList.toggle("theme-night", isNight);
      metricMode.textContent = isNight ? "Night" : "Day";
      themeToggle.innerHTML = isNight
        ? '<i class="bi bi-sun me-1"></i> Day Mode'
        : '<i class="bi bi-moon-stars me-1"></i> Night Mode';
      localStorage.setItem("guest-theme", isNight ? "night" : "day");
      syncJsStatus();
    }

    function initTheme() {
      const saved = localStorage.getItem("guest-theme");
      setTheme(saved === "night");
    }

    function showToastr(head, msg, ms) {
      const root = document.getElementById("toastr-root");
      const ttl = typeof ms === "number" ? ms : 1900;
      const item = document.createElement("div");
      item.className = "toast-glass";
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
      if (jsThemeState) {
        jsThemeState.textContent = metricMode.textContent;
      }
      if (jsRowsState) {
        jsRowsState.textContent = metricRows.textContent;
      }
      if (jsMenuState) {
        jsMenuState.textContent = menuOpen ? "Open" : "Closed";
      }
    }

    function buildRadialMenu() {
      radialMenu.innerHTML = "";
      const isMobile = window.innerWidth < 600;
      const start = 100;
      const end = 182;
      const itemCount = menuConfig.length;
      const step = itemCount > 1 ? (end - start) / (itemCount - 1) : 0;

      // Keep enough chord distance between adjacent buttons so icons do not overlap.
      const btnSize = isMobile ? 40 : 46;
      const minGap = btnSize + (isMobile ? 6 : 8);
      const spanRad = (end - start) * Math.PI / 180;
      const perStepRad = itemCount > 1 ? spanRad / (itemCount - 1) : 0.6;
      const safeDenominator = Math.max(0.15, Math.sin(perStepRad / 2));
      const minRadius = minGap / (2 * safeDenominator);
      const baseRadius = isMobile ? 94 : 110;
      const radius = Math.max(baseRadius, Math.ceil(minRadius));

      menuConfig.forEach(function (item, idx) {
        const angleDeg = start + (idx * step);
        const angle = angleDeg * Math.PI / 180;
        const x = Math.cos(angle) * radius;
        const y = -Math.sin(angle) * radius;

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
      closeMenuTimer = setTimeout(closeMenu, 4500);
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
        showToastr("JS Action", "Toastr triggered from JS panel.");
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
    setInterval(updateClock, 1000);

    initTheme();
    updateMetrics();
    buildRadialMenu();
    syncJsStatus();

    if (modalEl && window.bootstrap && window.bootstrap.Modal) {
      glassModalInstance = new window.bootstrap.Modal(modalEl);
    }

    themeToggle.addEventListener("click", function () {
      setTheme(!body.classList.contains("theme-night"));
      showToastr("Theme updated", "Switched to " + metricMode.textContent + " mode");
    });

    fabLogoBtn.addEventListener("click", function (e) {
      e.preventDefault();
      if (menuOpen) {
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

    tableFilter.addEventListener("input", function (e) {
      filterTableRows(e.target.value);
    });

    document.getElementById("demoForm").addEventListener("submit", function (e) {
      e.preventDefault();
      showToastr("Form submitted", "Input captured and reset complete.");
      e.target.reset();
    });

    document.addEventListener("shown.bs.modal", function (event) {
      const firstInput = event.target.querySelector("input,textarea,select");
      if (firstInput) {
        setTimeout(function () {
          firstInput.focus();
        }, 120);
      }
    });
  </script>
</body>
</html>