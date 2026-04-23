<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a3a; background: #fff; }

    .header { background: #0b044d; color: #fff; padding: 18px 24px 14px; margin-bottom: 0; }
    .header-top { display: table; width: 100%; }
    .header-left { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; text-align: right; vertical-align: middle; }
    .header h1 { font-size: 20px; font-weight: 700; letter-spacing: 1px; margin-bottom: 2px; }
    .header .sub { font-size: 10px; color: #c5c0f0; }
    .header .emp-name { font-size: 13px; font-weight: 600; color: #fff; }
    .header .emp-id { font-size: 10px; color: #c5c0f0; margin-top: 2px; }

    .section { padding: 14px 24px; }
    .section-label { font-size: 8.5px; font-weight: 700; color: #9999bb; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 8px; border-bottom: 1px solid #e5e4f0; padding-bottom: 4px; }

    .two-col { display: table; width: 100%; margin-bottom: 0; }
    .col { display: table-cell; vertical-align: top; width: 50%; padding-right: 12px; }
    .col:last-child { padding-right: 0; padding-left: 12px; }

    .info-block { background: #f7f6ff; border-radius: 8px; padding: 12px 14px; margin-bottom: 14px; }

    .detail-row { display: table; width: 100%; padding: 6px 0; border-bottom: 1px solid #eeecfc; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { display: table-cell; font-size: 10px; color: #9999bb; font-weight: 500; width: 55%; }
    .detail-value { display: table-cell; font-size: 10px; color: #0b044d; font-weight: 700; text-align: right; }
    .detail-value.red { color: #8e1e18; }
    .detail-value.green { color: #15803d; }

    .total-row { border-top: 2px solid #e5e4f0!important; margin-top: 4px; padding-top: 8px!important; }
    .total-row .detail-label { font-weight: 700; color: #0b044d; }
    .total-row.red-total .detail-label { color: #8e1e18; }

    .net-pay-box { background: #f0fdf4; border-radius: 8px; padding: 12px 14px; margin-top: 10px; display: table; width: 100%; }
    .net-pay-label { display: table-cell; font-size: 12px; font-weight: 700; color: #15803d; }
    .net-pay-value { display: table-cell; font-size: 16px; font-weight: 700; color: #15803d; text-align: right; }

    .attendance-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
    .attendance-table th { background: #0b044d; color: #fff; font-size: 8.5px; font-weight: 700; padding: 6px 8px; text-align: left; }
    .attendance-table td { font-size: 9px; padding: 5px 8px; border-bottom: 1px solid #eeecfc; color: #1a1a3a; }
    .attendance-table tr:nth-child(even) td { background: #f7f6ff; }
    .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 8px; font-weight: 700; }
    .badge-present { background: #e8f9ef; color: #15803d; }
    .badge-late { background: #fefce8; color: #a16207; }
    .badge-absent { background: #fdf0ef; color: #8e1e18; }

    .absent-deduct-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
    .absent-deduct-table th { background: #fdf0ef; color: #8e1e18; font-size: 8.5px; font-weight: 700; padding: 5px 8px; text-align: left; }
    .absent-deduct-table td { font-size: 9px; padding: 5px 8px; border-bottom: 1px solid #eeecfc; }
    .absent-deduct-table tr:nth-child(even) td { background: #fff8f8; }

    .divider { height: 1px; background: #e5e4f0; margin: 0 24px; }
    .page-footer { text-align: center; font-size: 8px; color: #9999bb; padding: 10px 24px; border-top: 1px solid #e5e4f0; margin-top: 10px; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-top">
        <div class="header-left">
            <h1>PAYSLIP</h1>
            <div class="sub">{{ config('app.month') }} &nbsp;·&nbsp; Pay Period</div>
        </div>
        <div class="header-right">
            <div class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
            <div class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; {{ $employee->position->title }}</div>
            <div class="emp-id">{{ $employee->department->name }}</div>
        </div>
    </div>
</div>

{{-- EMPLOYEE INFO + EARNINGS/DEDUCTIONS --}}
<div class="section">
    <div class="two-col">

        {{-- LEFT: Employee & Attendance Info --}}
        <div class="col">
            <div class="info-block">
                <div class="section-label">Employee Information</div>
                <div class="detail-row">
                    <span class="detail-label">Position</span>
                    <span class="detail-value">{{ $employee->position->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">{{ $employee->department->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Employment Type</span>
                    <span class="detail-value">{{ ucfirst($employee->employment_type) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pay Period</span>
                    <span class="detail-value">{{ config('app.month') }}</span>
                </div>
            </div>

            <div class="info-block">
                <div class="section-label">Attendance Summary</div>
                <div class="detail-row">
                    <span class="detail-label">Hours Worked</span>
                    <span class="detail-value">{{ $employee->hoursWorked() }} hrs</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Overtime Hours</span>
                    <span class="detail-value">{{ $employee->overtimeWorked() }} hrs</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Days Worked</span>
                    <span class="detail-value">{{ $employee->daysWorked() }} days</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Days Late</span>
                    <span class="detail-value red">{{ $lateCount }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Days Absent</span>
                    <span class="detail-value red">{{ $absentCount }}</span>
                </div>
                <div class="detail-row total-row">
                    <span class="detail-label">Total Hours Worked</span>
                    <span class="detail-value">{{ $employee->totalHoursWorked() }} hrs</span>
                </div>
            </div>
        </div>

        {{-- RIGHT: Earnings & Deductions --}}
        <div class="col">
            <div class="info-block">
                <div class="section-label">Earnings</div>
                <div class="detail-row">
                    <span class="detail-label">Basic Pay</span>
                    <span class="detail-value">&#8369;{{ number_format($employee->salary->amount ?? 0, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Overtime Pay</span>
                    <span class="detail-value">&#8369;{{ number_format($employee->overtimePay(), 2) }}</span>
                </div>
                <div class="detail-row total-row">
                    <span class="detail-label">Gross Pay</span>
                    <span class="detail-value">&#8369;{{ number_format($employee->grossPay(), 2) }}</span>
                </div>
            </div>

            <div class="info-block">
                <div class="section-label">Deductions</div>
                <div class="detail-row">
                    <span class="detail-label">GSIS</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->gsisContribution(), 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">PhilHealth</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->philHealthContribution(), 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pag-Ibig</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->pagIbigContribution(), 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Withholding Tax</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->withholdingTax(), 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Optional Deductions</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->optionalDeductions(), 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Absent/Late Deductions</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->absentDeductions(), 2) }}</span>
                </div>
                <div class="detail-row total-row red-total">
                    <span class="detail-label">Total Deductions</span>
                    <span class="detail-value red">&#8369;{{ number_format($employee->totalDeductions(), 2) }}</span>
                </div>

                <div class="net-pay-box">
                    <span class="net-pay-label">NET PAY</span>
                    <span class="net-pay-value">&#8369;{{ number_format($employee->netPay(), 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ABSENT/LATE DEDUCTION BREAKDOWN --}}
@if($absentLateAttendances->count() > 0)
<div class="divider"></div>
<div class="section">
    <div class="section-label">Absent &amp; Late Deduction Breakdown</div>
    <table class="absent-deduct-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Daily Rate</th>
                <th>Deduction Rate</th>
                <th>Amount Deducted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absentLateAttendances as $record)
            @php
                $rate = $employee->dailyRate();
                $deductAmount = $record->attendance_status === 'Absent' ? $rate : ($rate * 0.5);
                $deductLabel = $record->attendance_status === 'Absent' ? '100% of daily rate' : '50% of daily rate';
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                <td>
                    @if($record->attendance_status === 'Absent')
                        <span class="badge badge-absent">Absent</span>
                    @else
                        <span class="badge badge-late">Late</span>
                    @endif
                </td>
                <td>&#8369;{{ number_format($rate, 2) }}</td>
                <td>{{ $deductLabel }}</td>
                <td style="color:#8e1e18;font-weight:700">&#8369;{{ number_format($deductAmount, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align:right;font-weight:700;color:#8e1e18;font-size:10px">Total Absent/Late Deductions</td>
                <td style="color:#8e1e18;font-weight:700">&#8369;{{ number_format($employee->absentDeductions(), 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif

{{-- ATTENDANCE RECORDS --}}
@if($attendances->count() > 0)
<div class="divider"></div>
<div class="section">
    <div class="section-label">Attendance Records — {{ config('app.month') }}</div>
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Break</th>
                <th>Overtime</th>
                <th>Total Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr>
                <td>{{ \Carbon\Carbon::parse($att->date)->format('M d, Y') }}</td>
                <td>{{ $att->time_in ?? '--:--' }}</td>
                <td>{{ $att->time_out ?? '--:--' }}</td>
                <td>{{ $att->break_start && $att->break_end ? $att->break_start . ' - ' . $att->break_end : 'N/A' }}</td>
                <td>{{ $att->overtime_in && $att->overtime_out ? $att->overtime_in . ' - ' . $att->overtime_out : 'N/A' }}</td>
                <td>{{ number_format(($att->total_minutes / 60) + ($att->overtime_minutes / 60), 2) }} h</td>
                <td>
                    @if($att->attendance_status === 'Present')
                        <span class="badge badge-present">Present</span>
                    @elseif($att->attendance_status === 'Late')
                        <span class="badge badge-late">Late</span>
                    @else
                        <span class="badge badge-absent">{{ $att->attendance_status }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="page-footer">
    Generated on {{ now()->format('F d, Y \a\t h:i A') }} &nbsp;·&nbsp; {{ config('app.name') }} Payroll System
</div>

</body>
</html>
