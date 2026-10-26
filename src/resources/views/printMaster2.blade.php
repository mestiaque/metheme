<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&text=%E0%A7%A6%E0%A7%A7%E0%A7%A8%E0%A7%A9%E0%A7%AA%E0%A7%AB%E0%A7%AC%E0%A7%AD%E0%A7%AE%E0%A7%AF%E0%A7%B3&display=swap">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            font-family: 'Times New Roman', Times, 'Noto Sans Bengali', 'Hind Siliguri', serif;
            font-size: 14px;
            color: #333;
            background: #fff;
        }
        .container {
            width: 100%;
            /* width: 210mm; */
            padding: 15px;
            margin-top: 3rem;
            margin-left: auto;
            margin-right: auto;
        }

        .fixed-top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 3rem !important;
        }

        /* Buttons (hidden in print) */
        .no-print {
            display: flex;
            align-items: center;
            justify-content: center;          /* both centered: Close, then Print */
            margin-bottom: 15px;
            padding: 5px 10px;
            /* glass: the page shows through, blurred, with a 10% blue tint (savex blue) */
            background: linear-gradient(rgba(15, 155, 214, .10), rgba(15, 155, 214, .10)), rgba(255, 255, 255, .45);
            -webkit-backdrop-filter: blur(12px) saturate(160%);
            backdrop-filter: blur(12px) saturate(160%);
            border-bottom: 1px solid rgba(255, 255, 255, .6);
            box-shadow: 0 4px 24px rgba(15, 23, 42, .08);
        }
        /* Print / Close: .btn-savex style (goo-bubble fill on hover, press on click); encodex.css isn't loaded here */
        .no-print button {
            position: relative;
            z-index: 1;
            isolation: isolate;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 34px;
            padding: 0 20px;
            margin: 0 5px;
            background: transparent;
            color: var(--btn-color);
            border: none;
            border-radius: 8px;
            font: 700 13px/1 'Hind Siliguri', sans-serif;
            text-transform: uppercase;
            letter-spacing: .5px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .25);
            transition: color .15s ease, box-shadow .3s, transform .15s ease;
        }
        .no-print button svg { width: 15px; height: 15px; flex-shrink: 0; }
        .no-print button::before {          /* outline, above the bubbles */
            content: "";
            position: absolute;
            z-index: 1;
            inset: 0;
            border: 2px solid var(--btn-color);
            border-radius: 8px;
            pointer-events: none;
        }
        .no-print button:hover { color: #fff; box-shadow: 0 6px 18px rgba(0, 0, 0, .4); }
        .no-print button:active { transform: scale(.95); }
        .no-print button:focus-visible { outline: 2px solid #60a5fa; outline-offset: 3px; }
        .no-print .btn-print { --btn-color: #28a745; }
        .no-print .btn-close { --btn-color: #dc3545; }

        .btn-fx__inner {
            position: absolute;
            z-index: -1;
            inset: 0;
            overflow: hidden;
            border-radius: 8px;
            background: #fff;
        }
        .btn-fx__blobs { position: relative; display: block; height: 100%; filter: url('#print-btn-goo'); }
        .btn-fx__blob {
            position: absolute;
            top: 2px;
            width: 25%;
            height: 100%;
            background: var(--btn-color);
            border-radius: 100%;
            transform: translate3d(0, 150%, 0) scale(1.4);
            transition: transform .45s;
        }
        .btn-fx__blob:nth-child(1) { left: 0%; }
        .btn-fx__blob:nth-child(2) { left: 30%; transition-delay: .08s; }
        .btn-fx__blob:nth-child(3) { left: 60%; transition-delay: .16s; }
        .btn-fx__blob:nth-child(4) { left: 90%; transition-delay: .24s; }
        .no-print button:hover .btn-fx__blob { transform: translateZ(0) scale(1.4); }
        @media (prefers-reduced-motion: reduce) {
            .btn-fx__blob, .no-print button { transition: none; }
        }

        /* Header */
        .print-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #909090;
        }
        .company-info {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 5px;
        }
        .company-logo { width: 60px; height: 60px; object-fit: contain; }
        .company-name { font-size: 30px; font-weight: bold; text-transform: uppercase; color: #0047ab;}
        .company-address, .company-contact { font-size: 12px; color: #555; }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #333;
            font-size: 12px;
            text-align: left;
        }
        th { background: #cfcfcf !important; }

        /* Signature */
        .print-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }
        .print-time{
            float: right !important;
            font-size: 10px !important;
            background: white !important;
            color: #000000a8 !important;
            padding: 2px 2px !important;
            vertical-align: bottom !important;
            margin-top: 1rem !important;
        }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #333; margin-top: 30px; padding-top: 5px; }

        /* Print adjustments */
        @media print {
            .no-print { display: none; }
            body { margin: 1mm; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { padding: 0; margin-top: 0rem; width: 100%; }
            .company-name { font-size: 28px !important; }
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .grandtotal-row { background: #d1d1d1 !important; font-weight: bold; }
        .subtotal-row { background: #e7e7e7 !important; font-weight: bold; }
    </style>
    @stack('css')
</head>
<body>
    <div class="no-print fixed-top">
        {{-- goo filter: melts the bubbles together like liquid --}}
        <svg width="0" height="0" style="position: absolute" aria-hidden="true"><defs>
            <filter id="print-btn-goo">
                <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"></feGaussianBlur>
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7" result="goo"></feColorMatrix>
                <feBlend in2="goo" in="SourceGraphic" result="mix"></feBlend>
            </filter>
        </defs></svg>
        <button type="button" class="btn-close" onclick="mePrintForceClose()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            @lang('me::me.Close')
            <span class="btn-fx__inner"><span class="btn-fx__blobs"><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span></span></span>
        </button>
        <button type="button" class="btn-print" onclick="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            @lang('me::me.Print')
            <span class="btn-fx__inner"><span class="btn-fx__blobs"><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span><span class="btn-fx__blob"></span></span></span>
        </button>
    </div>
    <script>
        // Browsers only let a script close a tab that a script opened (master opens print links with
        // window.open, so this closes them). If the browser still refuses (e.g. a QR scan opened the page)
        // and the tab has a previous page, go back to it; otherwise stay here (never redirect to login).
        function mePrintForceClose() {
            window.close();
            setTimeout(function () {
                if (!window.closed && history.length > 1) history.back();
            }, 150);
        }
    </script>

    <div class="container">
        @yield('contents')
    </div>
    @stack('js')
</body>
</html>
