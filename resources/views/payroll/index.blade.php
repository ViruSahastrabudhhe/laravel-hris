@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner" style="margin-bottom:22px">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#d9bb00" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
        </div>
        <div>
            <h2>Payroll for {{ $calendar->format('F Y') }}</h2>
            <p>{{ $calendar->startOfMonth()->format('M d') }} – {{ $calendar->endOfMonth()->format('M d, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $employees->count() }} Employees</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Payroll Summary</p>
            <p class="table-sub">Monthly payroll breakdown for all employees</p>
        </div>
        <div class="table-actions">
            <button class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export Payroll
            </button>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Hours</th>
                    <th>Gross Pay</th>
                    <th>GSIS</th>
                    <th>PhilHealth</th>
                    <th>Pag-Ibig</th>
                    <th>Tax</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="pay-cell">{{ $employee->totalHoursWorked() }}h</span></td>
                    <td><span class="pay-cell">₱{{ number_format($employee->grossPay(), 2) }}</span></td>
                    <td><span class="deduction">₱{{ number_format($employee->gsisContribution(), 2) }}</span></td>
                    <td><span class="deduction">₱{{ number_format($employee->philHealthContribution(), 2) }}</span></td>
                    <td><span class="deduction">₱{{ number_format($employee->pagIbigContribution(), 2) }}</span></td>
                    <td><span class="deduction">₱{{ number_format($employee->withholdingTax(), 2) }}</span></td>
                    <td>
                        <span class="deduction">₱{{ number_format($employee->optionalDeductions(), 2) }}</span>
                        <a href="{{ route('employee_deductions.index') }}" style="font-size:10px;color:#8e1e18;display:block;margin-top:2px">View</a>
                    </td>
                    <td><span class="net-pay">₱{{ number_format($employee->netPay(), 2) }}</span></td>
                    <td>
                        <button class="btn-view" onclick="viewPayslip({{ $employee->id }})">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            View
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d9d9ee" stroke-width="2"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No employees on payroll</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->isNotEmpty())
    <div class="table-footer">
        <span>Total Employees: <strong>{{ $employees->count() }}</strong></span>
        <span>Total Net Pay: <strong>₱{{ number_format($employees->sum(fn($e) => $e->netPay()), 2) }}</strong></span>
    </div>
    @endif
</div>

<script>
function viewPayslip(employeeId) {
    alert('Payslip view for Employee ID: ' + employeeId + '\n\nThis would open a detailed payslip modal or page.');
}
</script>

@endsection
