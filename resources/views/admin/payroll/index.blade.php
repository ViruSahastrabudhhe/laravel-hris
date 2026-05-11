@extends('layouts.admin')

@php
    $hideChat = true;
    use Carbon\Carbon;

    $grossPayroll = 0;
    $totalNetPay = 0;
    $totalDeductions = 0;
    $pendingPayroll = 0;
    foreach ($employees as $employee) {
        $grossPayroll += $employee->grossPay();
        $totalNetPay += $employee->netPay();
        $totalDeductions += $employee->totalDeductions();

        $currentRecord = $employee->payrollRecords
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        if ($currentRecord && $currentRecord->status === 'Pending') {
            $pendingPayroll++;
        }
    }

    $totalPersonnel = $employees->count();
    $payDate = now()->format('M d, Y');
    $defaultStartDate = Carbon::now()->copy()->startOfMonth()->format('Y-m-d');
    $defaultEndDate = Carbon::now()->copy()->endOfMonth()->format('Y-m-d');
@endphp

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
            <h2>Payroll Management</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Payroll Processing</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="15" height="15" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="payroll-search" placeholder="Search payroll..." class="recruit-search">
        </div>
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
            <p class="stat-sub">For {{ config('app.carbon_month') }}</p>
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
            <p class="stat-sub">After compensations</p>
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
            <p class="stat-label">Pending Records</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
        </div>
        <p class="stat-value stat-value-large">{{ $pendingPayroll }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">{{ $totalPersonnel }} processed</p>
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
            <input type="date" id="start-date" class="filter-select payroll-date-input" value="{{ $defaultStartDate }}">
            <input type="date" id="end-date" class="filter-select payroll-date-input" value="{{ $defaultEndDate }}">
            <select class="filter-select" id="month-filter">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                        {{ Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select class="filter-select" id="year-filter">
                @foreach(range(now()->year - 2, now()->year) as $y)
                    <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select class="filter-select" id="dept-filter">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select class="filter-select" id="status-filter">
                <option value="All">All Status</option>
                <option value="Processed">Processed</option>
                <option value="Pending" selected>Pending</option>
                <option value="Draft">Draft</option>
            </select>
            <a href="{{ route('payroll.exportPayroll') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <button class="modal-btn-primary" onclick="openPayrollRunModal()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Run Payroll
            </button>
        </div>
    </div>

    <div class="payroll-summary-bar">
        <div class="psummary-item">
            <span>Gross Total</span>
            <strong class="gross-total">₱{{ number_format($grossPayroll, 2) }}</strong>
        </div>
        <div class="psummary-divider"></div>
        <div class="psummary-item">
            <span>Total Deductions</span>
            <strong class="deduction">₱{{ number_format($totalDeductions, 2) }}</strong>
        </div>
        <div class="psummary-divider"></div>
        <div class="psummary-item">
            <span>Total Net Pay</span>
            <strong class="net-pay">₱{{ number_format($totalNetPay, 2) }}</strong>
        </div>
        <div class="psummary-divider"></div>
        <div class="psummary-item">
            <span>Pay Date</span>
            <strong>{{ $payDate }}</strong>
        </div>
        <div class="psummary-divider"></div>
        <div class="psummary-item">
            <span>Records</span>
            <strong>{{ $totalPersonnel }}</strong>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="payroll-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Gross Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
                    @php
                        $record = $employee->payrollRecords
                            ->where('month', now()->month)
                            ->where('year', now()->year)
                            ->first();
                        $status = $record->status ?? 'Draft';
                    @endphp
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
                    <td>
                        <span class="deduction">₱{{ number_format($employee->totalDeductions(), 2) }}</span>
                    </td>
                    <td><span class="net-pay">₱{{ number_format($employee->netPay(), 2) }}</span></td>
                    <td>
                        <span class="badge-status {{ strtolower($status) === 'processed' ? 'processed' : 'pending' }}">{{ $status }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button type="button" class="btn-view" onclick="openModal('payslipModal-{{ $employee->id }}')">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button type="button" class="btn-edit" {{ $record === null ? '' : 'hidden' }}
                                    onclick="openSinglePayrollModal({{ Js::from([
                                        'id' => $employee->id,
                                        'name' => $employee->first_name . ' ' . $employee->last_name,
                                        'department' => $employee->department->name,
                                        'grossPay' => number_format($employee->grossPay(), 2, '.', ''),
                                        'totalDeductions' => number_format($employee->totalDeductions(), 2, '.', ''),
                                        'netPay' => number_format($employee->netPay(), 2, '.', ''),
                                    ]) }})" title="Run Payroll">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Payslip Modal --}}
                <div class="modal-overlay" id="payslipModal-{{ $employee->id }}" style="display:none" onclick="closeModal('payslipModal-{{ $employee->id }}')">
                    <div class="modal-box" onclick="event.stopPropagation()">
                        <div class="modal-header">
                            <div>
                                <span class="modal-eyebrow">PAYSLIP · {{ strtoupper(config('app.carbon_month')) }}</span>
                                <h3 class="modal-title">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                                <p class="modal-sub">{{ $employee->position->title }} · {{ $employee->department->name }}</p>
                            </div>
                            <button class="modal-close" onclick="closeModal('payslipModal-{{ $employee->id }}')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-emp-row">
                                <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }};width:48px;height:48px;border-radius:12px;font-size:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                    {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                                </div>
                                <div>
                                    <p class="modal-emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    <span class="badge-status processed">{{ ucfirst($employee->employment_type) }}</span>
                                </div>
                            </div>
                            <div class="modal-section-label">EARNINGS</div>
                            <div class="modal-row"><span>Basic Pay</span><strong>₱{{ number_format($employee->salary->amount ?? 0, 2) }}</strong></div>
                            <div class="modal-row"><span>Overtime Pay</span><strong>₱{{ number_format($employee->overtimePay(), 2) }}</strong></div>
                            <div class="modal-row total"><span>Gross Pay</span><strong>₱{{ number_format($employee->grossPay(), 2) }}</strong></div>
                            <div class="modal-section-label">DEDUCTIONS</div>
                            <div class="modal-row"><span>GSIS</span><span class="modal-deduct">₱{{ number_format($employee->gsisContribution(), 2) }}</span></div>
                            <div class="modal-row"><span>PhilHealth</span><span class="modal-deduct">₱{{ number_format($employee->philHealthContribution(), 2) }}</span></div>
                            <div class="modal-row"><span>Pag-Ibig</span><span class="modal-deduct">₱{{ number_format($employee->pagIbigContribution(), 2) }}</span></div>
                            <div class="modal-row"><span>Withholding Tax</span><span class="modal-deduct">₱{{ number_format($employee->withholdingTax(), 2) }}</span></div>
                            <div class="modal-row"><span>Optional Deductions</span><span class="modal-deduct">₱{{ number_format($employee->optionalDeductions(), 2) }}</span></div>
                            <div class="modal-row"><span>Absent/Late</span><span class="modal-deduct">₱{{ number_format($employee->absentDeductions(), 2) }}</span></div>
                            <div class="modal-row total"><span>Total Deductions</span><span class="modal-deduct">₱{{ number_format($employee->totalDeductions(), 2) }}</span></div>
                            <div class="modal-net-row">
                                <span>NET PAY</span>
                                <strong>₱{{ number_format($employee->netPay(), 2) }}</strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-btn-ghost" onclick="closeModal('payslipModal-{{ $employee->id }}')">Close</button>
                            <a href="{{ route('payroll.exportPayslip', $employee->id) }}" class="modal-btn-primary">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <p>Showing <strong>{{ $employees->count() }}</strong> of <strong>{{ $totalPersonnel }}</strong> records</p>
    </div>
</div>

{{-- Run Payroll Modal --}}
<div class="modal-overlay" id="payroll-run-modal" style="display:none" onclick="closeModal('payroll-run-modal')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">PAYROLL PROCESSING</span>
                <h3 class="modal-title" id="payroll-modal-title">Process Payroll?</h3>
            </div>
            <button class="modal-close" onclick="closeModal('payroll-run-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('payroll.bulkStore') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-confirm-info">
                    <input type="hidden" name="month" id="payroll-run-month">
                    <input type="hidden" name="year" id="payroll-run-year">
                    <div class="modal-row"><span>Total Personnel</span><strong>{{ $totalPersonnel }}</strong></div>
                    <div class="modal-row"><span>Gross Payroll</span><strong>₱{{ number_format($grossPayroll, 2) }}</strong></div>
                    <div class="modal-row"><span>Pay Date</span><strong>{{ now()->format(config('app.day_month')) }}</strong></div>
                </div>
                <p class="modal-alert">⚠ This will finalize payroll for all listed employees. Ensure all DTR and leave records are updated before proceeding.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('payroll-run-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">Confirm & Process</button>
            </div>
        </form>
    </div>
</div>

{{-- Single Payroll Modal --}}
<div class="modal-overlay" id="single-payroll-modal" style="display:none" onclick="closeModal('single-payroll-modal')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">PAYROLL PROCESSING</span>
                <h3 class="modal-title" id="single-payroll-modal-title">Process Payroll?</h3>
            </div>
            <button class="modal-close" onclick="closeModal('single-payroll-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="single-payroll-form" method="POST">
            @csrf
            <input type="hidden" name="month" id="single-payroll-month">
            <input type="hidden" name="year" id="single-payroll-year">
            <div class="modal-body">
                <div class="modal-confirm-info">
                    <input type="hidden" name="employee_id" id="single-payroll-employee-id">
                    <input type="hidden" name="total_earnings" id="single-payroll-total-earnings">
                    <input type="hidden" name="total_deductions" id="single-payroll-total-deductions">
                    <input type="hidden" name="net_pay" id="single-payroll-net-pay">
                    <input type="hidden" name="status" value="Processed">
                    <div class="modal-row"><span>Employee</span><strong id="single-payroll-name"></strong></div>
                    <div class="modal-row"><span>Department</span><strong id="single-payroll-dept"></strong></div>
                    <div class="modal-row"><span>Gross Pay</span><strong id="single-payroll-gross"></strong></div>
                    <div class="modal-row"><span>Total Deductions</span><strong id="single-payroll-deductions"></strong></div>
                    <div class="modal-row total"><span>Net Pay</span><strong id="single-payroll-net"></strong></div>
                    <div class="modal-row"><span>Pay Date</span><strong>{{ now()->format(config('app.day_month')) }}</strong></div>
                </div>
                <p class="modal-alert">⚠ This will finalize payroll for this employee. Ensure DTR and leave records are updated before proceeding.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('single-payroll-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">Confirm & Process</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const payroll_table = $('#payroll-table').DataTable({
        columnDefs: [{ orderable: false, targets: [5] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No payroll records found', },
        dom: 'rtip',
    });

    $('#payroll-search').on('keyup', function() {
        payroll_table.search(this.value).draw();
    });

    $('#dept-filter').on('change', function() {
        payroll_table.column(1).search(this.value).draw();
    });

    const colors = ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'];
    const nowMonth = {{ now()->month }};
    const nowYear = {{ now()->year }};

    function setDateRangeFromMonthYear(month, year) {
        const start = new Date(year, month - 1, 1);
        const end = new Date(year, month, 0);

        $('#start-date').val(start.toISOString().slice(0, 10));
        $('#end-date').val(end.toISOString().slice(0, 10));
    }

    function fetchPayroll() {
        const startDate = $('#start-date').val();
        const endDate = $('#end-date').val();
        const month = startDate ? new Date(startDate).getMonth() + 1 : parseInt($('#month-filter').val());
        const year = startDate ? new Date(startDate).getFullYear() : parseInt($('#year-filter').val());

        if (!startDate) {
            setDateRangeFromMonthYear(month, year);
        }

        $.get('{{ route('payroll.filter') }}', { month, year }, function(res) {
            payroll_table.clear();

            res.employees.forEach(function(e) {
                const initials = e.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
                const color = colors[e.id % 5];
                const empId = 'EMP-' + String(e.id).padStart(3, '0');

                payroll_table.row.add([
                    `<div class="emp-cell"><div class="emp-avatar" style="background:${color}">${initials}</div><div><p class="emp-name">${e.name}</p><p class="emp-id">${empId}</p></div></div>`,
                    `<span class="dept-tag">${e.department}</span>`,
                    `<span class="pay-cell">₱${e.gross_pay}</span>`,
                    `<span class="deduction">₱${e.compensations}</span>`,
                    `<span class="net-pay">₱${e.net_pay}</span>`,
                    '',
                ]);
            });

            payroll_table.draw();
        });
    }

    $('#month-filter, #year-filter').on('change', function() {
        const month = parseInt($('#month-filter').val());
        const year = parseInt($('#year-filter').val());

        setDateRangeFromMonthYear(month, year);
        fetchPayroll();
    });

    $('#start-date, #end-date').on('change', function() {
        const startDate = $('#start-date').val();
        const endDate = $('#end-date').val();
        const month = startDate ? new Date(startDate).getMonth() + 1 : parseInt($('#month-filter').val());
        const year = startDate ? new Date(startDate).getFullYear() : parseInt($('#year-filter').val());

        if (startDate) {
            $('#month-filter').val(month);
            $('#year-filter').val(year);
        }

        if (!endDate && startDate) {
            const end = new Date(year, month, 0).toISOString().slice(0, 10);
            $('#end-date').val(end);
        }

        fetchPayroll();
    });

    $('#status-filter').on('change', function() {
        const status = this.value;
        payroll_table.column(5).search(status === 'All' ? '' : '^' + status + '$', true, false).draw();
    });

    window.openPayrollRunModal = function() {
        const startDate = $('#start-date').val();
        const endDate = $('#end-date').val();
        const month = startDate ? new Date(startDate).getMonth() + 1 : parseInt($('#month-filter').val());
        const year = startDate ? new Date(startDate).getFullYear() : parseInt($('#year-filter').val());
        const starts = startDate ? new Date(startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : null;
        const ends = endDate ? new Date(endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : null;

        $('#payroll-run-month').val(month);
        $('#payroll-run-year').val(year);

        let titleText = startDate ? `Process ${starts}` : 'Process Payroll';
        if (ends) {
            titleText += ` — ${ends}`;
        }
        titleText += ' Payroll?';

        $('#payroll-modal-title').text(titleText);

        openModal('payroll-run-modal');
    };
});
</script>

<script>
    function formatPeso(value) {
        return '₱' + parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function openSinglePayrollModal(data) {
        const startDate = document.getElementById('start-date').value;
        const endDate   = document.getElementById('end-date').value;
        const month     = startDate ? new Date(startDate).getMonth() + 1 : document.getElementById('month-filter').value;
        const year      = startDate ? new Date(startDate).getFullYear() : document.getElementById('year-filter').value;
        const startText = startDate ? new Date(startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '';
        const endText   = endDate ? new Date(endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '';

        document.getElementById('single-payroll-modal-title').textContent = `Process ${startText}${endText ? ' — ' + endText : ''} Payroll?`;
        document.getElementById('single-payroll-name').textContent        = data.name;
        document.getElementById('single-payroll-dept').textContent        = data.department;
        document.getElementById('single-payroll-gross').textContent       = formatPeso(data.grossPay);
        document.getElementById('single-payroll-deductions').textContent  = formatPeso(data.totalDeductions);
        document.getElementById('single-payroll-net').textContent         = formatPeso(data.netPay);

        var url = "{{ route('payroll.store') }}";
        document.getElementById('single-payroll-form').action            = url;
        document.getElementById('single-payroll-month').value            = month;
        document.getElementById('single-payroll-year').value             = year;
        document.getElementById('single-payroll-employee-id').value      = data.id;
        document.getElementById('single-payroll-total-earnings').value   = data.grossPay;
        document.getElementById('single-payroll-total-deductions').value = data.totalDeductions;
        document.getElementById('single-payroll-net-pay').value          = data.netPay;

        openModal('single-payroll-modal');
    };
</script>
@endpush
