<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a3a; background: #fff; }

    .header { background: #0b044d; color: #fff; padding: 16px 24px; margin-bottom: 0; }
    .header-top { display: table; width: 100%; }
    .header-left { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; text-align: right; vertical-align: middle; }
    .header h1 { font-size: 18px; font-weight: 700; letter-spacing: 1px; margin-bottom: 2px; }
    .header .sub { font-size: 9px; color: #c5c0f0; }
    .header .meta { font-size: 10px; color: #c5c0f0; }

    .summary-row { display: table; width: 100%; background: #f7f6ff; border-bottom: 2px solid #e5e4f0; }
    .summary-cell { display: table-cell; padding: 10px 16px; text-align: center; border-right: 1px solid #e5e4f0; }
    .summary-cell:last-child { border-right: none; }
    .summary-label { font-size: 8px; color: #9999bb; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 3px; }
    .summary-value { font-size: 13px; font-weight: 700; color: #0b044d; }
    .summary-value.green { color: #15803d; }
    .summary-value.red { color: #8e1e18; }

    .section { padding: 12px 24px; }
    .section-label { font-size: 8.5px; font-weight: 700; color: #9999bb; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 8px; }

    table.main { width: 100%; border-collapse: collapse; }
    table.main thead th { background: #0b044d; color: #fff; font-size: 8.5px; font-weight: 700; padding: 7px 8px; text-align: left; }
    table.main thead th.right { text-align: right; }
    table.main tbody td { font-size: 9px; padding: 6px 8px; border-bottom: 1px solid #eeecfc; color: #1a1a3a; vertical-align: middle; }
    table.main tbody td.right { text-align: right; }
    table.main tbody tr:nth-child(even) td { background: #f7f6ff; }
    table.main tfoot td { font-size: 9.5px; font-weight: 700; padding: 7px 8px; border-top: 2px solid #0b044d; }
    table.main tfoot td.right { text-align: right; }

    .emp-name { font-weight: 700; color: #0b044d; font-size: 9.5px; }
    .emp-id { font-size: 8px; color: #9999bb; }
    .dept-tag { display: inline-block; background: #f0effe; color: #0b044d; border-radius: 4px; padding: 2px 6px; font-size: 8px; font-weight: 600; }
    .pay { color: #0b044d; font-weight: 700; }
    .deduct { color: #8e1e18; font-weight: 600; }
    .net { color: #15803d; font-weight: 700; }

    .page-footer { text-align: center; font-size: 8px; color: #9999bb; padding: 10px 24px; border-top: 1px solid #e5e4f0; margin-top: 10px; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-top">
        <div class="header-left">
            <h1>PAYROLL SUMMARY</h1>
            <div class="sub">{{ config('app.carbon_month') }} &nbsp;·&nbsp; All Employees</div>
        </div>
        <div class="header-right">
            <div class="meta">Generated: {{ now()->format('F d, Y') }}</div>
            <div class="meta">{{ $employees->count() }} Employees</div>
        </div>
    </div>
</div>

{{-- SUMMARY STATS --}}
<div class="summary-row">
    <div class="summary-cell">
        <div class="summary-label">Gross Payroll</div>
        <div class="summary-value">&#8369;{{ number_format($grossPayroll, 2) }}</div>
    </div>
    <div class="summary-cell">
        <div class="summary-label">Total Net Pay</div>
        <div class="summary-value green">&#8369;{{ number_format($totalNetPay, 2) }}</div>
    </div>
    <div class="summary-cell">
        <div class="summary-label">Total Deductions</div>
        <div class="summary-value red">&#8369;{{ number_format($totalDeductions, 2) }}</div>
    </div>
    <div class="summary-cell">
        <div class="summary-label">Employees</div>
        <div class="summary-value">{{ $employees->count() }}</div>
    </div>
</div>

{{-- TABLE --}}
<div class="section">
    <table class="main">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th class="right">Gross Pay</th>
                <th class="right">GSIS</th>
                <th class="right">PhilHealth</th>
                <th class="right">Pag-Ibig</th>
                <th class="right">Tax</th>
                <th class="right">Optional Ded.</th>
                <th class="right">Absent/Late</th>
                <th class="right">Net Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>
                    <div class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                    <div class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td><span class="dept-tag">{{ $employee->department->name }}</span></td>
                <td class="right pay">&#8369;{{ number_format($employee->grossPay(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->gsisContribution(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->philHealthContribution(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->pagIbigContribution(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->withholdingTax(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->optionalDeductions(), 2) }}</td>
                <td class="right deduct">&#8369;{{ number_format($employee->absentDeductions(), 2) }}</td>
                <td class="right net">&#8369;{{ number_format($employee->netPay(), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="color:#0b044d">TOTALS</td>
                <td class="right" style="color:#0b044d">&#8369;{{ number_format($grossPayroll, 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->gsisContribution()), 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->philHealthContribution()), 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->pagIbigContribution()), 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->withholdingTax()), 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->optionalDeductions()), 2) }}</td>
                <td class="right" style="color:#8e1e18">&#8369;{{ number_format($employees->sum(fn($e) => $e->absentDeductions()), 2) }}</td>
                <td class="right" style="color:#15803d">&#8369;{{ number_format($totalNetPay, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="page-footer">
    Generated on {{ now()->format('F d, Y \a\t h:i A') }} &nbsp;·&nbsp; {{ config('app.name') }} Payroll System
</div>

</body>
</html>
