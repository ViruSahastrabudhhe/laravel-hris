<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            color:#2b2b2b;
            padding:38px 45px;
            background:#ffffff;
        }

        .header{
            width:100%;
            border-bottom:2px solid #1f2937;
            padding-bottom:18px;
            margin-bottom:28px;
        }

        .header-table{
            width:100%;
        }

        .header-table td{
            vertical-align:middle;
        }

        .logo{
            width:78px;
        }

        .logo img{
            width:78px;
            height:78px;
            object-fit:contain;
        }

        .header-center{
            text-align:center;
        }

        .company-title{
            font-size:20px;
            font-weight:bold;
            letter-spacing:0.5px;
            color:#111827;
        }

        .company-subtitle{
            font-size:12px;
            margin-top:4px;
            color:#4b5563;
        }

        .document-title{
            margin-top:8px;
            font-size:15px;
            font-weight:bold;
            letter-spacing:1px;
            color:#111827;
        }

        .section-card{
            border:1px solid #d1d5db;
            border-radius:6px;
            padding:16px 18px;
            margin-bottom:22px;
        }

        .section-title{
            font-size:12px;
            font-weight:bold;
            margin-bottom:12px;
            color:#111827;
            text-transform:uppercase;
            letter-spacing:0.6px;
        }

        .details-table{
            width:100%;
        }

        .details-table td{
            padding:6px 0;
            vertical-align:top;
            font-size:11.5px;
        }

        .label{
            width:130px;
            color:#374151;
            font-weight:bold;
        }

        .colon{
            width:12px;
            text-align:center;
        }

        .salary-wrapper{
            width:100%;
            margin-top:8px;
        }

        .salary-wrapper td{
            vertical-align:top;
        }

        .table-gap{
            width:18px;
        }

        .salary-table{
            width:100%;
            border-collapse:collapse;
            border:1px solid #cbd5e1;
        }

        .salary-table th{
            background:#1f2937;
            color:#ffffff;
            padding:10px;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:0.5px;
            border:1px solid #1f2937;
        }

        .salary-table td{
            padding:9px 10px;
            border:1px solid #d1d5db;
            font-size:11.5px;
        }

        .salary-table tbody tr:nth-child(even){
            background:#f9fafb;
        }

        .amount{
            text-align:right;
            width:130px;
        }

        .total-row td{
            font-weight:bold;
            background:#eef2ff !important;
        }

        .summary-box{
            margin-top:25px;
            border:1.5px solid #1f2937;
            padding:18px;
            border-radius:6px;
        }

        .summary-table{
            width:100%;
        }

        .summary-table td{
            padding:4px 0;
        }

        .net-label{
            font-size:14px;
            font-weight:bold;
            text-transform:uppercase;
            color:#111827;
        }

        .net-amount{
            text-align:right;
            font-size:20px;
            font-weight:bold;
            color:#111827;
        }

        .net-words{
            margin-top:10px;
            font-size:12px;
            font-style:italic;
            color:#374151;
        }

        .signature-section{
            width:100%;
            margin-top:75px;
        }

        .signature-section td{
            width:50%;
            text-align:center;
        }

        .signature-title{
            font-size:12px;
            margin-bottom:52px;
            color:#374151;
        }

        .signature-line{
            width:220px;
            margin:0 auto;
            border-top:1px solid #111827;
        }

        .footer{
            margin-top:55px;
            text-align:center;
            font-size:10px;
            color:#6b7280;
            border-top:1px solid #d1d5db;
            padding-top:12px;
        }
    </style>

    @php
        function amountToWords($amount)
        {
            $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

            return ucfirst($formatter->format($amount)) . ' pesos only';
        }
    @endphp
</head>

<body>

{{-- HEADER --}}
<div class="header">

    <table class="header-table">
        <tr>

            {{-- LEFT LOGO --}}
            <td class="logo">
                <img src="{{ public_path('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Municipal Logo">
            </td>

            {{-- CENTER DETAILS --}}
            <td class="header-center">

                <div class="company-title">
                    {{ config('app.name') }}
                </div>

                <div class="company-subtitle">
                    JP Rizal Street, Poblacion Uno (Calle Real)<br>
                    Pagsanjan, Laguna
                </div>

                <div class="document-title">
                    EMPLOYEE PAYSLIP
                </div>

            </td>

            {{-- RIGHT SPACER --}}
            <td style="width:78px;"></td>

        </tr>
    </table>

</div>

{{-- EMPLOYEE INFORMATION --}}
<div class="section-card">

    <div class="section-title">
        Employee Information
    </div>

    <table class="details-table">
        <tr>

            {{-- LEFT --}}
            <td width="50%">

                <table class="details-table">

                    <tr>
                        <td class="label">Employee Name</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Department</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $employee->department->name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Designation</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $employee->position->title }}
                        </td>
                    </tr>

                </table>

            </td>

            {{-- RIGHT --}}
            <td width="50%">

                <table class="details-table">

                    <tr>
                        <td class="label">Date of Joining</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $employee->created_at->format('F d, Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Pay Period</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $record->payPeriod->name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Worked Days</td>
                        <td class="colon">:</td>
                        <td>
                            {{ $employee->daysWorked() }}
                        </td>
                    </tr>

                </table>

            </td>

        </tr>
    </table>

</div>

{{-- EARNINGS & DEDUCTIONS --}}
<table class="salary-wrapper">

    <tr>

        {{-- EARNINGS --}}
        <td width="50%">

            <table class="salary-table">

                <thead>
                <tr>
                    <th>Earnings</th>
                    <th class="amount">Amount</th>
                </tr>
                </thead>

                <tbody>

                @foreach($record->earnings()->get() as $earn)
                    <tr>
                        <td>{{ $earn->name }}</td>
                        <td class="amount">
                            &#8369;{{ number_format($earn->amount, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td>Total Earnings</td>
                    <td class="amount">
                        &#8369;{{ number_format($record->earnings()->sum('amount'), 2) }}
                    </td>
                </tr>

                </tbody>

            </table>

        </td>

        {{-- GAP --}}
        <td class="table-gap"></td>

        {{-- DEDUCTIONS --}}
        <td width="50%">

            <table class="salary-table">

                <thead>
                <tr>
                    <th>Deductions</th>
                    <th class="amount">Amount</th>
                </tr>
                </thead>

                <tbody>

                @foreach($record->deductions()->get() as $deduct)
                    <tr>
                        <td>{{ $deduct->name }}</td>
                        <td class="amount">
                            &#8369;{{ number_format($deduct->amount, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td>Total Deductions</td>
                    <td class="amount">
                        &#8369;{{ number_format($record->deductions()->sum('amount'), 2) }}
                    </td>
                </tr>

                </tbody>

            </table>

        </td>

    </tr>

</table>

{{-- NET PAY SUMMARY --}}
<div class="summary-box">

    <table class="summary-table">
        <tr>
            <td class="net-label">
                Net Pay
            </td>

            <td class="net-amount">
                &#8369;{{ number_format($record->amount_paid, 2) }}
            </td>
        </tr>
    </table>

    <div class="net-words">
        <strong>{{ amountToWords($record->amount_paid) }}</strong>
    </div>

</div>

{{-- SIGNATURES --}}
<table class="signature-section">
    <tr>

        <td>

            <div class="signature-title">
                Authorized Employer Signature
            </div>

            <div class="signature-line"></div>

        </td>

        <td>

            <div class="signature-title">
                Employee Signature
            </div>

            <div class="signature-line"></div>

        </td>

    </tr>
</table>

{{-- FOOTER --}}
<div class="footer">
    This document is system-generated and does not require a manual signature.
</div>

</body>
</html>
