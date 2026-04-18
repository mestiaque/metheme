<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', 'Dashboard') | {{ config('app.name', 'ESTIAQUE') }}</title>
    <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
    <meta name="title" content="@yield('meta-title', config('me_settings.meta_title'))" />
    <meta name="author" content="@yield('meta-author', config('me_settings.meta_author'))" />
    <meta name="description" content="@yield('meta-description', config('me_settings.meta_description'))" />
    <meta name="keywords" content="@yield('meta-keywords', config('me_settings.meta_keywords'))" />
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            color: #333;
            background: #fff;
        }
        .container {
            width: 100%;
            padding: 15px;
            margin-top: 3rem;
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
            text-align: center;
            margin-bottom: 15px;
            padding: 5px;
            background: #333;
        }
        .no-print button {
            padding: 8px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .btn-print { background: #28a745; }
        .btn-close { background: #dc3545; }

        /* Header */


        /* Report title */
        .report-title { font-size: 14px; font-weight: bold; margin: 3px 0px 5px 0px; text-transform: uppercase; }
        .report-title span {
            display: inline-block;
            padding: 3px 15px;
            background: #0000003b;
            color: #312e2e;
            border-radius: 4px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 4px;
            border: 1px solid #333;
            font-size: 12px;
            text-align: left;
        }
        thead th { background: #aec8f786; }
        tfoot th { background: #f5f5f5; }

        /* Signature */
        .print-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #333; margin-top: 30px; padding-top: 5px; }

        /* Print adjustments */
        @media print {
            .no-print { display: none; }
            body { margin: 1mm; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { padding: 0; margin-top: 0rem;}
        }
    </style>
    <style>
        .invoice-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            position: relative;
            z-index: 2;
        }
        .header-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }
        .header-info {
            flex: 1;
            text-align: center;
        }
        .header-info p {
            margin: 1px 0;
            font-size: 13px;
        }
        .header-qrcode {
            width: 90px;
            text-align: right;
        }

        .print-container {
            width: 100%;
            max-width: var(--p-width, 210mm);
            margin: 0 auto;
            padding: 10px;
            position: relative;
            z-index: 2;
            /* background: rebeccapurple; */
        }

        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        .watermark-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.06;
            pointer-events: none; /* মাউস ইভেন্ট আটকাবে না */
        }

        .watermark-bg img {
            max-width: 60%;
            max-height: 60%;
            transform: rotate(-45deg);
            /* ইমেজকে DIV-এর মাঝখানে রাখতে নিচের প্রপার্টিগুলো ব্যবহার করা হয়েছে */
            object-fit: contain;
            margin: auto;
        }

        .pfooter {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #333;
            padding-top: 5px;
            color: #000;
        }

        .report-header {
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
        }

        .report-header-top {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            min-height: 3.5rem;
        }

        .report-header-side {
            width: 15%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-header-side.text-end {
            justify-content: flex-end;
            align-items: flex-end;
        }

        .report-logo {
            height: 3rem;
            width: auto;
        }

        .report-shopname {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            font-size: 2rem;
            line-height: 1.1;
        }

        .print-time {
            display: inline-block;
            line-height: 1;
        }
        hr{
            border: none;
            border-top: 1px solid #33333357;
            margin: 10px 0;
        }


    </style>
    @stack('css')
</head>
<body>
    <div class="no-print fixed-top">
        <button class="btn-print" onclick="window.print()">
            <i class="fa fa-print"></i> Print
        </button>
        <button class="btn-close" onclick="closePrintTab()">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="container">
        <div class="watermark-bg">
            @if(get_image('app_logo'))
                <img src="{{ get_image('app_logo') }}">
            @else
                <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}">
            @endif
        </div>
        <!-- Header -->
        @if(isset($printType) && $printType == 'invoice')
            <div class="invoice-header-flex">
                <div>
                    @if(get_image('app_logo'))
                        <img src="{{ get_image('app_logo') }}" class="header-logo">
                    @else
                        <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}" class="header-logo">
                    @endif
                </div>

                <div class="header-info">
                    <h1 style="margin:0; margin-bottom:1px; font-size:2rem;">
                        {{ get_setting('shop_name', config('app.name')) }}
                    </h1>
                    <p>{{ get_setting('shop_address') }}</p>
                    <p>@lang('Phone'): {{ get_setting('shop_phone') }} | @lang('Email'): {{ get_setting('shop_email') }}</p>
                    <h5 style="margin:0; margin-top:2px;">
                        @if(isset($printTitle))
                            @lang($printTitle)
                        @else
                            @lang('INVOICE')
                        @endif
                    </h5>
                </div>
                <div class="header-qrcode">
                    @if(isset($printQr))
                        <div id="qrcode" data-printQr="{{$printQr}}"></div>
                    @endif
                </div>
            </div>
        @else
            <div class="report-header mb-2">
                <div class="report-header-top mb-0">
                    <div class="report-header-side">
                        @if(isset($logo) && $logo == false)
                            <!-- No logo -->
                        @else
                            @if(get_image('app_logo'))
                                <img src="{{ get_image('app_logo') }}" class="report-logo">
                            @else
                                <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}" class="report-logo">
                            @endif
                        @endif

                    </div>
                    <span class="report-shopname">
                        @if(isset($headerName) && $headerName)
                            {{ $headerName }}
                            @else
                            {{ get_setting('shop_name', config('app.name')) }}
                        @endif
                        <h5 class="report-title m-0"><span>{{ $printTitle ?? 'REPORT' }}</span></h5>
                    </span>
                    <div class="report-header-side text-end">
                        @if(isset($printTimeType) && $printTimeType == 'date')
                            <small class="print-time">Print: {{ formatDate(now()) }}</small>
                        @else
                            <small class="print-time">Print: {{ formatDateTime(now()) }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @yield('contents')
        <div class=" print-footer">
            <div class="sig signature-box">
                <div class="signature-line"></div>
                Prepared By
            </div>
            <div class="sig signature-box">
                <div class="signature-line"></div>
                Checked By
            </div>
            <div class="sig signature-box">
                <div class="signature-line"></div>
                Authorized Signature
            </div>
        </div>
        <div class="pfooter">
            @lang('This is a computer-generated invoice.') | @lang('Developed by: ENcodeX')
        </div>
    </div>

    <script>
        function closePrintTab() {
            window.open('', '_self');
            window.close();

            setTimeout(function () {
                if (window.history.length > 1) {
                    window.history.back();
                    return;
                }

                window.location.replace('about:blank');

                setTimeout(function () {
                    window.close();
                }, 100);
            }, 100);
        }
    </script>
</body>
</html>

{{-- printTitle ,
    @yield('contents'),
    printQr,
    printType,
    header(true/false)
    footer(true/false)
    signature(true/false)
    printTimeType(date/datetime)
--}}
