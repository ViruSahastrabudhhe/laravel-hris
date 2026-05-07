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
            color:#000;
            padding:40px 55px;
        }

        .text-center{
            text-align:center;
        }

        .company-name{
            font-size:15px;
            margin-top:5px;
        }

        .company-address{
            font-size:12px;
            line-height:1.4;
            margin-top:4px;
        }

        .top-info{
            width:100%;
            margin-top:45px;
        }

        .top-info td{
            vertical-align:top;
            width:50%;
        }

        .info-table{
            width:100%;
        }

        .info-table td{
            padding:4px 0;
            font-size:12px;
        }

        .label{
            width:120px;
        }

        .colon{
            width:10px;
            text-align:center;
        }

        .salary-table{
            width:100%;
            border-collapse:collapse;
            margin-top:45px;
            border:1px solid #000;
        }

        .salary-table th{
            border:1px solid #000;
            background:#d9d9d9;
            padding:8px;
            font-size:13px;
            text-align:center;
        }

        .salary-table td{
            border-left:1px solid #000;
            border-right:1px solid #000;
            padding:7px 10px;
            font-size:12px;
            vertical-align:top;
        }

        .amount{
            text-align:right;
            width:120px;
        }

        .total-row td{
            padding-top:25px;
            font-weight:bold;
        }

        .net-pay-row td{
            font-weight:bold;
            padding-top:4px;
        }

        .net-pay-section{
            text-align:center;
            margin-top:50px;
        }

        .net-pay-amount{
            font-size:18px;
            font-weight:bold;
        }

        .net-pay-words{
            font-size:14px;
            margin-top:5px;
        }

        .signature-section{
            width:100%;
            margin-top:80px;
        }

        .signature-section td{
            width:50%;
            text-align:center;
        }

        .signature-label{
            margin-bottom:55px;
            font-size:13px;
        }

        .signature-line{
            width:220px;
            margin:0 auto;
            border-top:1px solid #000;
        }

        .footer{
            text-align:center;
            margin-top:60px;
            font-size:12px;
        }
    </style>

    @php
        function amountToWords($amount)
        {
            $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

            return ucfirst($formatter->format($amount));
        }
    @endphp
</head>

<body>

{{-- HEADER --}}
<div class="text-center">
    <h1>Payslip</h1>

    <div class="company-name">
        {{ config('app.name') }}
    </div>

    <div class="company-address">
        JP Rizal Street, Poblacion Uno (Calle Real)<br>
        Pagsanjan, Laguna
    </div>
</div>

{{-- EMPLOYEE DETAILS --}}
<table class="top-info">
    <tr>

        {{-- LEFT --}}
        <td>
            <table class="info-table">
                <tr>
                    <td class="label">Date of Joining</td>
                    <td class="colon">:</td>
                    <td>{{ $employee->created_at->format('Y-m-d') }}</td>
                </tr>

                <tr>
                    <td class="label">Pay Period</td>
                    <td class="colon">:</td>
                    <td>{{ config('app.carbon_month') }}</td>
                </tr>

                <tr>
                    <td class="label">Worked Days</td>
                    <td class="colon">:</td>
                    <td>{{ $employee->daysWorked() }}</td>
                </tr>
            </table>
        </td>

        {{-- RIGHT --}}
        <td>
            <table class="info-table">
                <tr>
                    <td class="label">Employee Name</td>
                    <td class="colon">:</td>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                </tr>

                <tr>
                    <td class="label">Designation</td>
                    <td class="colon">:</td>
                    <td>{{ $employee->position->title }}</td>
                </tr>

                <tr>
                    <td class="label">Department</td>
                    <td class="colon">:</td>
                    <td>{{ $employee->department->name }}</td>
                </tr>
            </table>
        </td>

    </tr>
</table>

{{-- EARNINGS & DEDUCTIONS --}}
<table class="salary-table">

    <thead>
    <tr>
        <th>Earnings</th>
        <th>Amount</th>
        <th>Deductions</th>
        <th>Amount</th>
    </tr>
    </thead>

    <tbody>

    <tr>
        <td>Basic Pay</td>
        <td class="amount">
            &#8369;{{ number_format($employee->salary->amount ?? 0, 2) }}
        </td>

        <td>GSIS</td>
        <td class="amount">
            &#8369;{{ number_format($employee->gsisContribution(), 2) }}
        </td>
    </tr>

    <tr>
        <td>Overtime Pay</td>
        <td class="amount">
            &#8369;{{ number_format($employee->overtimePay(), 2) }}
        </td>

        <td>PhilHealth</td>
        <td class="amount">
            &#8369;{{ number_format($employee->philHealthContribution(), 2) }}
        </td>
    </tr>

    <tr>
        <td>Meal Allowance</td>
        <td class="amount">
            &#8369;0.00
        </td>

        <td>Pag-Ibig</td>
        <td class="amount">
            &#8369;{{ number_format($employee->pagIbigContribution(), 2) }}
        </td>
    </tr>

    <tr>
        <td>Incentive Pay</td>
        <td class="amount">
            &#8369;0.00
        </td>

        <td>Withholding Tax</td>
        <td class="amount">
            &#8369;{{ number_format($employee->withholdingTax(), 2) }}
        </td>
    </tr>

    <tr>
        <td>House Rent Allowance</td>
        <td class="amount">
            &#8369;0.00
        </td>

        <td>Optional Deductions</td>
        <td class="amount">
            &#8369;{{ number_format($employee->optionalDeductions(), 2) }}
        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>

        <td>Absent/Late Deductions</td>
        <td class="amount">
            &#8369;{{ number_format($employee->absentDeductions(), 2) }}
        </td>
    </tr>

    {{-- TOTALS --}}
    <tr class="total-row">
        <td>Total Earnings</td>
        <td class="amount">
            &#8369;{{ number_format($employee->grossPay(), 2) }}
        </td>

        <td>Total Deductions</td>
        <td class="amount">
            &#8369;{{ number_format($employee->totalDeductions(), 2) }}
        </td>
    </tr>

    <tr class="net-pay-row">
        <td colspan="2"></td>

        <td>Net Pay</td>
        <td class="amount">
            &#8369;{{ number_format($employee->netPay(), 2) }}
        </td>
    </tr>

    </tbody>

</table>

{{-- NET PAY TEXT --}}
<div class="net-pay-section">

    <div class="net-pay-amount">
        &#8369;{{ number_format($employee->netPay(), 2) }}
    </div>

    <div class="net-pay-words">
        {{ amountToWords($employee->netPay()) }}
    </div>

</div>

{{-- SIGNATURES --}}
<table class="signature-section">
    <tr>
        <td>
            <div class="signature-label">
                Employer Signature
            </div>

            <div class="signature-line"></div>
        </td>

        <td>
            <div class="signature-label">
                Employee Signature
            </div>

            <div class="signature-line"></div>
        </td>
    </tr>
</table>

{{-- FOOTER --}}
<div class="footer">
    This is system generated payslip
</div>

</body>
</html>
