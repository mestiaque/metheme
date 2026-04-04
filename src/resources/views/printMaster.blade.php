@extends('me::master')

{{-- @section('title', trans('Print')) --}}


@push('buttons')
  {{-- @component('me::components.btn.add-button', [
      'route' => route('me.roles.create'),
      'text' => __('Add Role'),
      'class' => 'btn-encodex-create'
  ])
  @endcomponent --}}
  <button class="btn btn-sm btn-encodex-print2" onclick="window.print()">@lang('Print Invoice')</button>
  @if(isset($backUrl) && $backUrl !== '')
    <a href="#" class="btn btn-sm btn-encodex-create">@lang('Back')</a>
  @endif
@endpush

@section('content')


{{-- PRINTABLE AREA START --}}
<div id="printableArea" class="">

    <!-- 👉 এখানে আমি তোমার প্রথম মেসেজের পুরো invoice HTML শুদ্ধভাবে বসিয়ে দিচ্ছি  -->

        @stack('pcss')
            {{-- তোমার invoice এর CSS 그대로 --}}
            <style>

                /* :root {
                    --p-width: 210mm;
                    --p-height: 297mm;
                    --p-margin: 10mm;
                } */


                .invoice-header-flex {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 5px;
                    border-bottom: 1px solid #33333357;
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


                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid #333;
                }
                th, td {
                    padding: 4px;
                    text-align: left;
                }
                th {
                    background-color: transparent;
                    color: #000;
                }
                .text-end {
                    text-align: right;
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
                @media print {

                    body * {
                        visibility: hidden !important;
                    }

                    #printableArea,
                    #printableArea * {
                        visibility: visible !important;
                        color: #000 !important;
                        border-color: #333 !important;
                        background: transparent !important;
                    }

                    #printableArea {
                        display: block !important;
                        position: fixed !important;
                        top: 0;
                        left: 0;
                        width: 100% !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    @page {
                        margin: var(--p-margin, 2mm)
                    }
                    body{
                        font-size: 12px;
                    }
                    th {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                        background: transparent !important;
                    }
                    .shawdow{
                        box-shadow:none !important;
                    }

                    .watermark-bg {
                        /* fixed পজিশন দিলে এটি প্রতিটি প্রিন্টেড পেজে অটোমেটিক চলে যাবে */
                        position: fixed !important;
                        top: 0;
                        left: 0;
                        width: 100vw;
                        height: 100vh;
                        z-index: -1000; /* কন্টেন্টের নিচে রাখার জন্য */
                        display: flex !important;
                        align-items: center;
                        justify-content: center;
                        pointer-events: none;
                        opacity: 0.08 !important; /* প্রিন্টে হালকা দেখানোর জন্য */
                        -webkit-print-color-adjust: exact; /* কালার এবং অপাসিটি ঠিক রাখতে */
                    }

                    .watermark-bg img {
                        width: 60%; /* পেজের সাইজ অনুযায়ী অ্যাডজাস্ট হবে */
                        max-width: 500px;
                        transform: rotate(-45deg);
                    }


                }


            </style>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <div class="pbody">
            <div class="print-container shadow">
                <!-- Watermark -->
                <div class="watermark-bg">
                    @if(get_setting('shop_logo'))
                        <img src="{{ route('shop_logo.show', get_setting('shop_logo')) }}">
                    @else
                        <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}">
                    @endif
                </div>

                <!-- Header -->
                @if(isset($printType) && $printType == 'invoice')
                    <div class="invoice-header-flex">
                        <div>
                            @if(get_setting('shop_logo'))
                                <img src="{{ route('shop_logo.show', get_setting('shop_logo')) }}" class="header-logo">
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


                    <div class="report-header text-center mb-2" style="border-bottom: 1px solid #33333357;">
                        <div class="report-header-top d-inline-flex align-items-center mb-0">
                            @if(get_setting('shop_logo'))
                                <img src="{{ route('shop_logo.show', get_setting('shop_logo')) }}" class="report-logo" style="height: 2rem; margin-right: 0.5rem;">
                            @else
                                <img src="{{ asset('assets/img/default-img/Encodex_c.png') }}" class="report-logo" style="height: 2rem; margin-right: 0.5rem;">
                            @endif
                            <span class="report-shopname" style="font-size: 2rem;">
                                {{ get_setting('shop_name', config('app.name')) }}
                            </span>
                        </div>

                        <h5 class="report-title m-0">{{ $printTitle ?? 'REPORT' }}</h5>
                    </div>

                @endif

                <!-- Table -->
                    @yield('printCont')


                <!-- Footer -->
                <div class="pfooter">
                    @lang('This is a computer-generated invoice.') | @lang('Developed by: ENcodeX')
                </div>
            </div>
            @stack('pjs')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const qrText = @json($printQr ?? "ESTIAQUE");
                    const qrElement = document.getElementById("qrcode");
                    if (qrElement) {
                        new QRCode(qrElement, {
                            text: qrText,
                            width: 90,
                            height: 90
                        });
                    }
                });
            </script>
        </div>

</div>
{{-- PRINTABLE AREA END --}}

@endsection

