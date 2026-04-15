@extends('layouts.admin')

@php
$grossPayroll = 0;
$totalNetPay = 0;
$totalDeductions = 0;
foreach ($employees as $employee) {
    $grossPayroll += $employee->grossPay();
    $totalNetPay += $employee->netPay();
    $totalDeductions += $employee->totalDeductions();
}
@endphp

@section('page-content')
<div class="welcome-banner" style="margin-bottom:22px">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#d9bb00" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
        </div>
        <div>
            <h2>Payroll Management</h2>
            <p>{{ now()->format('D - F j, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $employees->count() }} Employees</span>
    </div>
</div>

<div class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Gross Payroll</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($grossPayroll, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">For {{ now()->format('F, Y') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Net Pay</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($totalNetPay, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">After deductions</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Deductions</p>
            <div class="stat-icon-wrap" style="background: #fdf0ef">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($totalDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">Mandatory, Optional</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">System Status</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
        </div>
        <p class="stat-value" style="font-size:20px">Active</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">All systems operational</p>
        </div>
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

    <div class="table-wrapper" style="padding: 20px 20px 16px">
        <table class="payroll-table" id="attendance-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Department</th>
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
                    <td><span class="dept-tag">{{ $employee->department->name }}</span></td>
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
                        <a href="{{ route('payroll.show', $employee->id) }}">
                            <button class="btn-view">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                View
                            </button>
                        </a>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function viewPayslip(employeeId) {
    alert('Payslip view for Employee ID: ' + employeeId + '\n\nThis would open a detailed payslip modal or page.');
}

$(function () {
    $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 10] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No payroll recordsfound', },
    });

    $('#select-all').on('change', function () {
        $('.row-check').prop('checked', this.checked);
        updateBulkBar();
    });

    $(document).on('change', '.row-check', function () {
        if (!this.checked) $('#select-all').prop('checked', false);
        updateBulkBar();
    });

    function updateBulkBar() {
        const checked = $('.row-check:checked');
        if (checked.length) {
            $('#bulk-btn').show();
            $('#bulk-count').text(checked.length);
            $('#bulk-ids').html(checked.map((_, el) =>
                `<input type="hidden" name="ids[]" value="${el.value}">`
            ).get().join(''));
        } else {
            $('#bulk-btn').hide();
        }
    }
});
</script>
@endpush

@endsection
