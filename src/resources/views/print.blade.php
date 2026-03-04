<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
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
        .print-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #909090;
        }
        .company-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        .company-logo { width: 60px; height: 60px; object-fit: contain; }
        .company-name { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .company-address, .company-contact { font-size: 12px; color: #555; }

        /* Report title */
        .report-title { font-size: 14px; font-weight: bold; margin: 7px 0 0px; text-transform: uppercase; }
        .report-title span {
            display: inline-block;
            padding: 5px 15px;
            background: #333;
            color: #fff;
            border-radius: 4px;
        }

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
        th { background: #f5f5f5; }

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
    @stack('css')
</head>
<body>
    <div class="no-print fixed-top">
        <button class="btn-print" onclick="window.print()">
            <i class="fa fa-print"></i> Print
        </button>
        <button class="btn-close" onclick="window.close()">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="print-header">
            <div class="company-info">
                @if(general() && general()->logo())
                <img src="{{ asset(general()->logo()) }}" alt="Logo" class="company-logo">
                @endif
                <div class="company-name">{{ general()->title ?? 'Company Name' }}</div>
            </div>
            @if(general())
            <div class="company-address">
                {{ general()->address_one ?? '' }}
            </div>
            <div class="company-contact">
                Phone: {{ general()->mobile ?? '' }} | Email: {{ general()->email ?? '' }}
            </div>
            @endif
            <div class="report-title"><span>@yield('title')</span></div>
        </div>

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
    </div>
</body>
</html>
