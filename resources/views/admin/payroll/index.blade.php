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

        if ($currentRecord && $currentRecord->status === 'Draft') {
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
        <button class="btn-view" id="filter-btn">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M3 5h18l-7 8v5l-4 2v-7L3 5z"></path>
            </svg>
            Filter
        </button>
        <div>
            <select class="filter-select" id="pay-period-filter"
                onclick="filterPayroll(this)"
            >
                @foreach ($periods as $period)
                <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
{{--            <select class="filter-select" id="month-filter">--}}
{{--                @foreach(range(1,12) as $m)--}}
{{--                    <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>--}}
{{--                        {{ Carbon::create()->month($m)->format('F') }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <select class="filter-select" id="year-filter">--}}
{{--                @foreach(range(now()->year - 2, now()->year) as $y)--}}
{{--                    <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
        </div>
        <div class="recruit-search-wrap">
            <svg width="15" height="15" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="banner-search" placeholder="Search..." class="recruit-search" oninput="$('.tab-pane.active table').DataTable().search(this.value).draw()">
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

<div class="tab-buttons">
    <button class="tab-btn active" onclick="switchView('records', this)">Payroll Records</button>
    <button class="tab-btn" onclick="switchView('periods', this)">Pay Periods</button>
</div>

<div id="tab-records" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Payroll Summary</p>
                <p class="table-sub">Monthly payroll breakdown for all employees</p>
            </div>
            <div class="table-actions">
                <select class="filter-select" id="dept-filter">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter">
                    <option value="All">All Status</option>
                    <option value="Processed">Processed</option>
                    <option value="Draft">Draft</option>
                </select>
                <a href="{{ route('payroll.exportPayroll') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export CSV
                </a>
                <button onclick="openBulkCreateRecord()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Create Records
                </button>
                <button class="modal-btn-primary" onclick="openPayrollRunModal()" hidden>
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Run Payroll
                </button>
            </div>
        </div>

        <div class="payroll-summary-bar" hidden>
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
            <table class="payroll-table" id="records-table">
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
                @forelse($records as $record)
                    <tr>
                        @php
                            $status = $record->status ?? 'Draft';
                        @endphp
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($record->employee->id % 5)] }}">
                                    {{ strtoupper(substr($record->employee->first_name, 0, 1) . substr($record->employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="emp-name">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</p>
                                    <p class="emp-id">EMP-{{ str_pad($record->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="dept-tag">{{ $record->employee->department->name }}</span></td>
                        <td><span class="pay-cell">₱{{ number_format($record->employee->grossPay(), 2) }}</span></td>
                        <td>
                            <span class="deduction">₱{{ number_format($record->employee->totalDeductions(), 2) }}</span>
                        </td>
                        <td><span class="net-pay">₱{{ number_format($record->employee->netPay(), 2) }}</span></td>
                        <td>
                            <span class="badge-status {{ strtolower($status) === 'processed' ? 'processed' : 'pending' }}">{{ $status }}</span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-view" onclick="openModal('payslipModal-{{ $record->employee->id }}')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" class="btn-edit" hidden
                                onclick="openSinglePayrollModal({{ Js::from([
                                            'id' => $record->employee->id,
                                            'name' => $record->employee->first_name . ' ' . $record->employee->last_name,
                                            'department' => $record->employee->department->name,
                                            'grossPay' => number_format($record->employee->grossPay(), 2, '.', ''),
                                            'totalDeductions' => number_format($record->employee->totalDeductions(), 2, '.', ''),
                                            'netPay' => number_format($record->employee->netPay(), 2, '.', ''),
                                        ]) }})" title="Run Payroll">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Payslip Modal --}}
                    <div class="modal-overlay" id="payslipModal-{{ $record->employee->id }}" style="display:none" onclick="closeModal('payslipModal-{{ $record->employee->id }}')">
                        <div class="modal-box" onclick="event.stopPropagation()">
                            <div class="modal-header">
                                <div>
                                    <span class="modal-eyebrow">PAYSLIP · {{ strtoupper(config('app.carbon_month')) }}</span>
                                    <h3 class="modal-title">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</h3>
                                    <p class="modal-sub">{{ $record->employee->position->title }} · {{ $record->employee->department->name }}</p>
                                </div>
                                <button class="modal-close" onclick="closeModal('payslipModal-{{ $record->employee->id }}')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-emp-row">
                                    <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($record->employee->id % 5)] }};width:48px;height:48px;border-radius:12px;font-size:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                        {{ strtoupper(substr($record->employee->first_name,0,1).substr($record->employee->last_name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="modal-emp-id">EMP-{{ str_pad($record->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        <span class="badge-status processed">{{ ucfirst($record->employee->employment_type) }}</span>
                                    </div>
                                </div>
                                <div class="modal-section-label">EARNINGS</div>
                                <div class="modal-row"><span>Basic Pay</span><strong>₱{{ number_format($record->employee->salary->amount ?? 0, 2) }}</strong></div>
                                <div class="modal-row"><span>Overtime Pay</span><strong>₱{{ number_format($record->employee->overtimePay(), 2) }}</strong></div>
                                <div class="modal-row total"><span>Gross Pay</span><strong>₱{{ number_format($record->employee->grossPay(), 2) }}</strong></div>
                                <div class="modal-section-label">DEDUCTIONS</div>
                                <div class="modal-row"><span>GSIS</span><span class="modal-deduct">₱{{ number_format($record->employee->gsisContribution(), 2) }}</span></div>
                                <div class="modal-row"><span>PhilHealth</span><span class="modal-deduct">₱{{ number_format($record->employee->philHealthContribution(), 2) }}</span></div>
                                <div class="modal-row"><span>Pag-Ibig</span><span class="modal-deduct">₱{{ number_format($record->employee->pagIbigContribution(), 2) }}</span></div>
                                <div class="modal-row"><span>Withholding Tax</span><span class="modal-deduct">₱{{ number_format($record->employee->withholdingTax(), 2) }}</span></div>
                                <div class="modal-row"><span>Optional Deductions</span><span class="modal-deduct">₱{{ number_format($record->employee->optionalDeductions(), 2) }}</span></div>
                                <div class="modal-row"><span>Absent/Late</span><span class="modal-deduct">₱{{ number_format($record->employee->absentDeductions(), 2) }}</span></div>
                                <div class="modal-row total"><span>Total Deductions</span><span class="modal-deduct">₱{{ number_format($record->employee->totalDeductions(), 2) }}</span></div>
                                <div class="modal-net-row">
                                    <span>NET PAY</span>
                                    <strong>₱{{ number_format($record->employee->netPay(), 2) }}</strong>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="modal-btn-ghost" onclick="closeModal('payslipModal-{{ $record->employee->id }}')">Close</button>
                                <a href="{{ route('payroll.exportPayslip', $record->employee->id) }}" class="modal-btn-primary">
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
    </div>
</div>

<div id="tab-periods" class="tab-pane">
    <div class="table-section" style="margin-bottom:22px">
        <div class="table-header">
            <div>
                <p class="table-title">Pay Periods</p>
                <p class="table-sub">{{ config('app.name') }} · Pay Periods</p>
            </div>
            <div class="table-actions">
                <select class="filter-select" id="status-filter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button class="btn-export" hidden>
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export
                </button>
                <button onclick="openCreatePayPeriod()" class="modal-btn-primary" style="gap:6px;display:inline-flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    New Period
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="periods-table">
                <thead>
                <tr>
                    <th>Pay Period ID</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($periods as $period)
                    @php
                        $statusClass = $period->is_active ? 'processed' : 'pending';
                    @endphp

                    <tr>
                        <td style="font-size:12.5px;color:#6b6a8a;font-weight:500">
                            PERIOD-{{ str_pad($period->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td><span class="position-cell">{{ $period->name }}</span></td>
                        <td><span class="dept-tag">{{ \Carbon\Carbon::parse($period->start_date)->format('M d, Y') }}</span></td>
                        <td><span class="dept-tag">{{ \Carbon\Carbon::parse($period->end_date)->format('M d, Y') }}</span></td>
                        <td>
                            @if($period->is_active)
                                <span class="badge-status processed">Active</span>
                            @else
                                <span class="badge-status pending">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                @if (!$period->is_active)
                                    <form action="{{ route('payroll.activatePeriod', $period->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-success" title="Toggle Active">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('payroll.deactivatePeriod', $period->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-danger" title="Toggle Active">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('payroll.destroyPeriod', $period->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this period?')"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Run Payroll Modal --}}
<div class="modal-overlay" id="payroll-run-modal" style="display:none">
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
        <form action="{{ route('payroll.bulkStoreRecord') }}" method="POST">
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
<div class="modal-overlay" id="single-payroll-modal" style="display:none">
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

{{-- Bulk Create Record Modal --}}
<div class="modal-overlay" id="bulkCreateRecordModal" style="display: none;">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">PAYROLL RECORDS</span>
                <h3 class="modal-title">Bulk Create Payroll Records</h3>
                <p class="modal-sub">Generate records for all employees</p>
            </div>
            <button class="modal-close" onclick="closeModal('bulkCreateRecordModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('payroll.bulkStoreRecord') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                @if($periods->isEmpty() || $periods->where('is_active', true)->isEmpty())
                    <div style="text-align:center;padding:32px 0">
                        <p style="font-size:13px;color:#6b6a8a;margin:0">No pay periods found. Create one first.</p>
                    </div>
                @else
                    <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#0b044d,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#0b044d;margin:0">All Employees</p>
                            <p style="font-size:11px;color:#9999bb;margin:2px 0 0">Payroll records will be created for every employee in the system.</p>
                        </div>
                    </div>
                    <input type="hidden" name="month" value="{{ now()->month }}">
                    <input type="hidden" name="year" value="{{ now()->year }}">
                    <div class="form-field">
                        <label>Pay Period <span style="color:#dc2626">*</span></label>
                        <select name="pay_period_id" required>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}">{{ $period->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('bulkCreateRecordModal')">Cancel</button>
                @if($periods->where('is_active', true)->isNotEmpty())
                    <button type="submit" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Bulk Create
                    </button>
                @else
                    <button type="button" class="modal-btn-primary" onclick="closeModal('bulkCreateRecordModal'); openCreatePayPeriod()">
                        Create Period First
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Create Pay Period Modal --}}
<div class="modal-overlay" id="createPayPeriodModal" style="display: none;">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">PAY PERIOD</span>
                <h3 class="modal-title">Create Pay period</h3>
                <p class="modal-sub">Define a new pay period</p>
            </div>
            <button class="modal-close" onclick="closeModal('createPayPeriodModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('payroll.storePeriod') }}" method="post">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Period Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="name" placeholder="e.g. May 2026 1-15" required>
                </div>
                <div class="form-field">
                    <label>Start Date <span style="color:#dc2626">*</span></label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="form-field">
                    <label>End Date <span style="color:#dc2626">*</span></label>
                    <input type="date" name="end_date" required>
                </div>
                <div class="form-field">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto">
                        <span>Set as Active Period</span>
                    </label>
                </div>
                <div style="background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12px;color:#92400e;margin-top:4px">
                    ⚠️ Setting this as active will overwrite the current active period.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('createPayPeriodModal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Period
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        let recordsTable;
        let periodsTable;

        if ($('#records-table').length) {
            performanceTable = $('#records-table').DataTable({
                columnDefs: [{ orderable: false, targets: [4] }],
                pageLength: 25,
                language: {
                    lengthMenu: 'Show _MENU_ entries',
                    emptyTable: 'No payroll records found'
                },
                dom: 'rtip',
            });
        }

        if ($('#periods-table').length) {
            cyclesTable = $('#periods-table').DataTable({
                columnDefs: [{ orderable: false, targets: [3] }],
                pageLength: 25,
                language: {
                    lengthMenu: 'Show _MENU_ entries',
                    emptyTable: 'No pay periods found'
                },
                dom: 'rtip',
            });
        }

        $('#banner-search').on('input', function () {
            const value = this.value;

            $('.tab-pane.active table').each(function () {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().search(value).draw();
                }
            });
        });

        $('#status-filter').on('change', function() {
            if (!recordsTable) return;

            const val = this.value ? '^' + this.value + '$' : '';
            recordsTable.column(3).search(val, true, false).draw();
        });

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

            function setDateRangeFromMonthYear(month, year) {
                const start = new Date(year, month - 1, 1);
                const end = new Date(year, month, 0);

                $('#start-date').val(start.toISOString().slice(0, 10));
                $('#end-date').val(end.toISOString().slice(0, 10));
            }

            function fetchPayroll(month, year, period) {
                // const startDate = $('#start-date').val();
                // const endDate = $('#end-date').val();
                //
                // if (!startDate) {
                //     setDateRangeFromMonthYear(month, year);
                // }

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

            function filterPayroll() {
                const month = document.getElementById('global-month-filter').val();
                const year = document.getElementById('year-month-filter').val();
                const period = document.getElementById('pay-period-filter').val();

                fetchPayroll(month, year, period);
            }

            $('#month-filter, #year-filter').on('change', function() {
                const month = parseInt($('#month-filter').val());
                const year = parseInt($('#year-filter').val());

                if (month === {{ now()->month }} && year === {{ now()->year }}) {
                    location.reload();
                    return;
                }

                setDateRangeFromMonthYear(month, year);
                fetchPayroll();
            });

            // $('#start-date, #end-date').on('change', function() {
            //     const startDate = $('#start-date').val();
            //     const endDate = $('#end-date').val();
            //     const month = startDate ? new Date(startDate).getMonth() + 1 : parseInt($('#month-filter').val());
            //     const year = startDate ? new Date(startDate).getFullYear() : parseInt($('#year-filter').val());
            //
            //     if (startDate) {
            //         $('#month-filter').val(month);
            //         $('#year-filter').val(year);
            //     }
            //
            //     if (!endDate && startDate) {
            //         const end = new Date(year, month, 0).toISOString().slice(0, 10);
            //         $('#end-date').val(end);
            //     }
            //
            //     fetchPayroll();
            // });

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
        function openCreatePayPeriod() {
            document.getElementById('createPayPeriodModal').style.display = 'flex';
        }
        function openBulkCreateRecord() {
            document.getElementById('bulkCreateRecordModal').style.display = 'flex';
        }
    </script>

    <script>
        function formatPeso(value) {
            return '₱' + parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function openSinglePayrollModal(data) {
            document.getElementById('single-payroll-modal-title').textContent = `Process ${startText}${endText ? ' — ' + endText : ''} Payroll?`;
            document.getElementById('single-payroll-name').textContent        = data.name;
            document.getElementById('single-payroll-dept').textContent        = data.department;
            document.getElementById('single-payroll-gross').textContent       = formatPeso(data.grossPay);
            document.getElementById('single-payroll-deductions').textContent  = formatPeso(data.totalDeductions);
            document.getElementById('single-payroll-net').textContent         = formatPeso(data.netPay);

            var url = "{{ route('payroll.storeRecord') }}";
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
