@extends('layouts.employee')

@php
    use App\Enums\LeaveStatus;
    use Carbon\Carbon;
@endphp

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <h2>Leave & Benefits</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; {{ $employee->position->title ?? 'Employee' }}{{ isset($employee->department) ? ' · ' . $employee->department->department_name : '' }}</p>
        </div>
    </div>
    <div class="banner-right">
        @php
            $vlBalance = $leaveBalances->firstWhere('type', 'Vacation Leave');
            $slBalance = $leaveBalances->firstWhere('type', 'Sick Leave');
        @endphp
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            VL: {{ $vlBalance ? $vlBalance->amount : 0 }} days
        </span>
        <span class="banner-badge outline">SL: {{ $slBalance ? $slBalance->amount : 0 }} days</span>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Leave Filed</p>
            <div class="stat-icon-wrap" style="background:#0b044d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $totalFiled }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">All time</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Days Used</p>
            <div class="stat-icon-wrap" style="background:#8e1e1815"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $totalDays }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Across all types</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Pending Requests</p>
            <div class="stat-icon-wrap" style="background:#d9bb0015"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d9bb00" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $totalPending }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Awaiting approval</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">VL + SL Balance</p>
            <div class="stat-icon-wrap" style="background:#15803d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        </div>
        <h2 class="stat-value">{{ ($vlBalance ? $vlBalance->amount : 0) + ($slBalance ? $slBalance->amount : 0) }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">{{ $vlBalance ? $vlBalance->amount : 0 }} VL · {{ $slBalance ? $slBalance->amount : 0 }} SL</p>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('leave', this)">My Leave Requests</button>
    <button class="tab-btn" onclick="switchTab('credits', this)">Leave Credits</button>
    <button class="tab-btn" onclick="switchTab('benefits', this)">My Benefits</button>
</div>

{{-- Tab: Leave Requests --}}
<div id="tab-leave" class="tab-content">
    <section class="table-section">
        <div class="table-header">
            <div>
                <h3 class="table-title">My Leave Requests</h3>
                <p class="table-sub" id="leaveCountSub">{{ $totalFiled }} of {{ $totalFiled }} records</p>
            </div>
            <div class="table-actions">
                <select class="filter-select" id="filterType" onchange="applyLeaveFilters()">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->leave_type }}">{{ $type->leave_type }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="filterStatus" onchange="applyLeaveFilters()">
                    <option value="">All Status</option>
                    @foreach($leaveStatuses as $status)
                        <option value="{{ $status->value }}">{{ $status->value }}</option>
                    @endforeach
                </select>
                <button class="modal-btn-primary" onclick="openLeaveCreateModal()">+ File Leave</button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="leaves-table">
                <thead>
                    <tr>
                        <th>Leave ID</th>
                        <th>Leave Type</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $leave)
                    <tr data-type="{{ $leave->leaveType->leave_type }}" data-status="{{ $leave->leave_status }}">
                        <td style="font-size:12px;color:#9999bb;font-weight:500;">LV-{{ Carbon::parse($leave->start_date)->format('Y') }}-{{ str_pad($leave->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-weight:600;">{{ $leave->leaveType->leave_type }}</td>
                        <td>{{ Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                        <td>{{ Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                        <td style="font-weight:700;">{{ $leave->leave_duration }}</td>
                        <td style="font-size:12.5px;color:#5a5888;">{{ $leave->leave_reason }}</td>
                        <td>
                            @if($leave->leave_status === LeaveStatus::Approved->value)
                                <span class="badge-status processed">Approved</span>
                            @elseif($leave->leave_status === LeaveStatus::Pending->value)
                                <span class="badge-status pending">Pending</span>
                            @else
                                <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-view" onclick="openDetailModal(
                                'LV-{{ Carbon::parse($leave->start_date)->format('Y') }}-{{ str_pad($leave->id, 3, '0', STR_PAD_LEFT) }}',
                                '{{ $leave->leaveType->leave_type }}',
                                '{{ Carbon::parse($leave->start_date)->format('M d, Y') }}',
                                '{{ Carbon::parse($leave->end_date)->format('M d, Y') }}',
                                {{ $leave->leave_duration }},
                                '{{ addslashes($leave->leave_reason) }}',
                                '{{ $leave->leave_status }}'
                            )">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;color:#9999bb;padding:32px;">No leave requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <span id="leaveCountFooter">Showing <strong>{{ $totalFiled }}</strong> of <strong>{{ $totalFiled }}</strong> records</span>
        </div>
    </section>
</div>

{{-- Tab: Leave Credits --}}
<div id="tab-credits" class="tab-content hidden">
    <div class="credits-grid">
        @php
            $creditColors = ['#0b044d','#15803d','#8e1e18','#d9bb00','#0369a1','#7c3aed'];
        @endphp
        @forelse($leaveBalances as $balance)
        @php
            $color    = $creditColors[$loop->index % count($creditColors)];
            $earned   = max($balance->amount, 1);
            $used     = $leaveRequests->where('leave_type_id', optional($leaveTypes->firstWhere('leave_type', $balance->type))->id)->sum('leave_duration');
            $remaining = max($balance->amount - $used, 0);
            $pct      = $earned > 0 ? round(($remaining / $earned) * 100) : 0;
        @endphp
        <div class="credit-card">
            <div class="credit-header">
                <div>
                    <label>{{ $balance->type }}</label>
                    <h2 style="color:{{ $color }}">{{ $remaining }}</h2>
                    <p>days remaining</p>
                </div>
                <div class="credit-stats">
                    <p>Earned: <strong>{{ $earned }}</strong></p>
                    <p>Used: <strong style="color:#8e1e18">{{ $used }}</strong></p>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
            </div>
            <div class="progress-labels">
                <span>0</span>
                <span>{{ $earned }} days max</span>
            </div>
        </div>
        @empty
        <p style="color:#9999bb;font-size:13px;">No leave balance records found.</p>
        @endforelse
    </div>
</div>

{{-- Tab: My Benefits --}}
<div id="tab-benefits" class="tab-content hidden">
    <div class="benefits-grid">
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">GSIS Premium</p>
                <div class="stat-icon-wrap" style="background:#0b044d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></div>
            </div>
            <h2 class="stat-value">₱{{ number_format($employee->gsisContribution(), 2) }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">Monthly contribution</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">PhilHealth</p>
                <div class="stat-icon-wrap" style="background:#15803d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
            </div>
            <h2 class="stat-value">₱{{ number_format($employee->philHealthContribution(), 2) }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#15803d"></span>
                <p class="stat-sub">Monthly contribution</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Pag-IBIG</p>
                <div class="stat-icon-wrap" style="background:#8e1e1815"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></div>
            </div>
            <h2 class="stat-value">₱{{ number_format($employee->pagIbigContribution(), 2) }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#8e1e18"></span>
                <p class="stat-sub">Monthly contribution</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Withholding Tax</p>
                <div class="stat-icon-wrap" style="background:#d9bb0015"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d9bb00" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
            </div>
            <h2 class="stat-value">₱{{ number_format($employee->withholdingTax(), 2) }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#d9bb00"></span>
                <p class="stat-sub">Monthly deduction</p>
            </div>
        </div>
    </div>

    <section class="table-section">
        <div class="table-header">
            <div>
                <h3 class="table-title">Benefits Breakdown — {{ now()->format('F Y') }}</h3>
                <p class="table-sub">Government-mandated contributions and deductions</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>Benefit / Contribution</th>
                        <th>Type</th>
                        <th>Monthly Amount</th>
                        <th>Annual Estimate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:600;">GSIS Premium</td>
                        <td><span class="dept-tag">Retirement & Insurance</span></td>
                        <td class="deduction">₱{{ number_format($employee->gsisContribution(), 2) }}</td>
                        <td style="font-weight:600;color:#5a5888;">₱{{ number_format(($employee->gsisContribution() * 12), 2) }}</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">PhilHealth</td>
                        <td><span class="dept-tag">Health Insurance</span></td>
                        <td class="deduction">₱{{ number_format($employee->philHealthContribution(), 2) }}</td>
                        <td style="font-weight:600;color:#5a5888;">₱{{ number_format(($employee->philHealthContribution() * 12), 2) }}</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Pag-IBIG</td>
                        <td><span class="dept-tag">Housing Fund</span></td>
                        <td class="deduction">₱{{ number_format($employee->pagIbigContribution(), 2) }}</td>
                        <td style="font-weight:600;color:#5a5888;">₱{{ number_format(($employee->pagIbigContribution() * 12), 2) }}</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Withholding Tax</td>
                        <td><span class="dept-tag">Government Tax</span></td>
                        <td class="deduction">₱{{ number_format($employee->withholdingTax(), 2) }}</td>
                        <td style="font-weight:600;color:#5a5888;">₱{{ number_format(($employee->withholdingTax() * 12), 2) }}</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <p>🔒 Benefits data is confidential and visible only to you.</p>
        </div>
    </section>
</div>

{{-- Detail Modal --}}
<div class="modal-overlay" id="detailModal" style="display:none" onclick="closeDetailModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUEST · <span id="detailId"></span></span>
                <h3 class="modal-title" id="detailType">—</h3>
                <p class="modal-sub" id="detailDates">—</p>
            </div>
            <button class="modal-close" onclick="closeDetailModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-emp-row">
                <div class="emp-avatar" style="background:#0b044d;width:48px;height:48px;border-radius:12px;font-size:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($employee->first_name ?? 'E', 0, 1) . substr($employee->last_name ?? '', 0, 1)) }}
                </div>
                <div>
                    <p class="modal-emp-id">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                    <span class="badge-status" id="detailStatus">—</span>
                </div>
            </div>
            <span class="modal-section-label">LEAVE DETAILS</span>
            <div class="modal-row"><span>Leave Type</span><strong id="detailType2">—</strong></div>
            <div class="modal-row"><span>Date From</span><strong id="detailFrom">—</strong></div>
            <div class="modal-row"><span>Date To</span><strong id="detailTo">—</strong></div>
            <div class="modal-row"><span>No. of Days</span><strong id="detailDays">—</strong></div>
            <span class="modal-section-label" style="margin-top:16px;">REASON</span>
            <div class="modal-row"><span id="detailReason">—</span></div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeDetailModal()">Close</button>
            <button class="modal-btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download
            </button>
        </div>
    </div>
</div>

{{-- File Leave Modal --}}
<div class="modal-overlay" id="leave-create-modal" style="display:none" onclick="closeLeaveCreateModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">NEW LEAVE REQUEST</span>
                <h3 class="modal-title">File a Leave</h3>
                <p class="modal-sub">{{ $employee->first_name }} {{ $employee->last_name }}</p>
            </div>
            <button class="modal-close" onclick="closeLeaveCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('my_leaves.store') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label>Leave Type <span style="color:#dc2626">*</span></label>
                        <select name="leave_type_id" required>
                            <option value="">Select leave type</option>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>No. of Days</label>
                        <input type="number" min="1" value="1" readonly id="leaveDaysDisplay">
                    </div>
                    <div class="form-field">
                        <label>Date From <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" id="leaveFrom" onchange="calcLeaveDays()" required>
                    </div>
                    <div class="form-field">
                        <label>Date To <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" id="leaveTo" onchange="calcLeaveDays()" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="leave_reason" rows="3" placeholder="Brief reason for leave" required>{{ old('leave_reason') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeLeaveCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.getElementById('tab-' + tabId).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function applyLeaveFilters() {
    const type   = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    const rows   = document.querySelectorAll('#leaves-table tbody tr[data-type]');
    let visible  = 0;
    rows.forEach(row => {
        const matchType   = !type   || row.dataset.type   === type;
        const matchStatus = !status || row.dataset.status === status;
        const show = matchType && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    const total = rows.length;
    document.getElementById('leaveCountFooter').innerHTML =
        'Showing <strong>' + visible + '</strong> of <strong>' + total + '</strong> records';
}

function openDetailModal(id, type, from, to, days, reason, status) {
    document.getElementById('detailId').textContent     = id;
    document.getElementById('detailType').textContent   = type;
    document.getElementById('detailType2').textContent  = type;
    document.getElementById('detailDates').textContent  = from + ' — ' + to;
    document.getElementById('detailFrom').textContent   = from;
    document.getElementById('detailTo').textContent     = to;
    document.getElementById('detailDays').textContent   = days + ' day' + (days > 1 ? 's' : '');
    document.getElementById('detailReason').textContent = reason;
    const statusEl = document.getElementById('detailStatus');
    statusEl.textContent = status;
    statusEl.className = 'badge-status ' + (status === 'Approved' ? 'processed' : (status === 'Pending' ? 'pending' : 'on-hold'));
    document.getElementById('detailModal').style.display = 'flex';
}

function calcLeaveDays() {
    const from = document.getElementById('leaveFrom').value;
    const to   = document.getElementById('leaveTo').value;
    if (from && to) {
        const diff = Math.max(1, Math.round((new Date(to) - new Date(from)) / 86400000) + 1);
        document.getElementById('leaveDaysDisplay').value = diff;
    }
}

function closeDetailModal()     { document.getElementById('detailModal').style.display = 'none'; }
function openLeaveCreateModal() { document.getElementById('leave-create-modal').style.display = 'flex'; }
function closeLeaveCreateModal(){ document.getElementById('leave-create-modal').style.display = 'none'; }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeDetailModal(); closeLeaveCreateModal(); }
});
</script>
@endpush
