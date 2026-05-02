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
    $departments = \App\Models\Department::findAllWithUserID()->get();
@endphp

@push('styles')
    <style>
        .payslip-grid { display:grid; grid-template-columns:1fr 1.4fr; gap:24px; }
        .payslip-block { background:#f7f6ff; border-radius:14px; padding:20px 22px; }
        .payslip-block-label { font-size:10.5px; font-weight:700; color:#9999bb; letter-spacing:1px; margin:0 0 14px; }
        .payslip-info-row { display:flex; align-items:center; gap:14px; margin-bottom:18px; }
        .payslip-detail-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #eeecfc; }
        .payslip-detail-row span { font-size:13px; color:#9999bb; font-weight:500; }
        .payslip-detail-row strong { font-size:13px; color:#0b044d; font-weight:600; }

        .modal-overlay { position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(11,4,77,0.6); backdrop-filter:blur(4px); display:flex; align-items:flex-start; justify-content:center; z-index:1000; padding:clamp(8px,3vw,20px); overflow-y:auto; }
        .modal-box { background:#fff; border-radius:16px; width:min(480px,100%); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:slideUp 0.3s ease; margin:auto; }
        @keyframes slideUp { from { transform:translateY(20px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        .modal-header { display:flex; justify-content:space-between; align-items:flex-start; padding:24px 24px 0; }
        .modal-eyebrow { font-size:10.5px; color:#9999bb; font-weight:700; letter-spacing:1px; }
        .modal-title { font-size:18px; font-weight:700; color:#0b044d; margin:4px 0 2px; }
        .modal-sub { font-size:13px; color:#6b6a8a; margin:0; }
        .modal-close { background:none; border:none; cursor:pointer; padding:4px; color:#9999bb; }
        .modal-close:hover { color:#0b044d; }
        .modal-body { padding:20px 24px; }
        .modal-emp-row { display:flex; align-items:center; gap:16px; margin-bottom:20px; padding:16px; background:#f7f6ff; border-radius:12px; }
        .modal-emp-id { font-size:11px; color:#9999bb; margin:0 0 4px; }
        .modal-section-label { font-size:10.5px; font-weight:700; color:#9999bb; letter-spacing:1px; margin-bottom:12px; }
        .modal-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0effe; }
        .modal-row span { font-size:13px; color:#9999bb; font-weight:600; }
        .modal-row strong { font-size:13px; color:#0b044d; font-weight:600; }
        .modal-row.total { border-bottom:2px solid #e5e4f0; padding-top:14px; margin-top:6px; }
        .modal-deduct { color:#8e1e18 !important; }
        .modal-net-row { display:flex; justify-content:space-between; align-items:center; background:#f0fdf4; border-radius:10px; padding:14px 16px; margin-top:10px; }
        .modal-net-row span { font-size:13px; color:#15803d; font-weight:700; }
        .modal-net-row strong { font-size:18px; color:#15803d; }
        .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px 24px; }
        .modal-btn-ghost { padding:9px 18px; border-radius:9px; border:1.5px solid #dddcf0; background:#fff; font-size:13px; font-weight:600; color:#6b6a8a; cursor:pointer; }
        .modal-btn-ghost:hover { border-color:#0b044d; color:#0b044d; }
        .modal-btn-primary { padding:9px 18px; border-radius:9px; border:none; background:linear-gradient(135deg,#0b044d,#1a0f6e); color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; }

        @media (max-width: 768px) {
            .payslip-grid { grid-template-columns:1fr; }
            .modal-box { border-radius:12px; }
            .modal-header { padding:16px 16px 0; }
            .modal-body { padding:14px 16px; }
            .modal-footer { padding:12px 16px 16px; }
        }
        @media (max-width: 400px) {
            .modal-overlay { padding:0; align-items:flex-end; }
            .modal-box { border-radius:16px 16px 0 0; width:100%; margin:0; }
        }
    </style>
@endpush

@section('page-content')
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
        <div class="table-actions" style="gap: 10px;">
            <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:absolute;left:10px;pointer-events:none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="payroll-search" placeholder="Search payroll..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
            </div>
            <select class="filter-select" id="dept-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('payroll.exportPayroll') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="#" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Run Payroll
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="attendance-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Gross Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
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
                        <a href="{{ route('employees.show', $employee) }}" style="font-size:10px;color:#8e1e18;display:block;margin-top:2px">View</a>
                    </td>
                    <td><span class="net-pay">₱{{ number_format($employee->netPay(), 2) }}</span></td>
                    <td>
                        <button class="btn-export" id="export-payslip" onclick="openModal('payslipModal')">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Payslip Modal --}}
<div class="modal-overlay" id="payslipModal" style="display:none" onclick="closeModal('payslipModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">PAYSLIP · {{ strtoupper(config('app.carbon_month')) }}</span>
                <h3 class="modal-title">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                <p class="modal-sub">{{ $employee->position->name }} · {{ $employee->department->name }}</p>
            </div>
            <button class="modal-close" onclick="closeModal('payslipModal')">
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
            <div class="modal-section-label" style="margin-top:16px">DEDUCTIONS</div>
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
            <button class="modal-btn-ghost" onclick="closeModal('payslipModal')">Close</button>
            <a href="{{ route('payroll.exportPayslip', $employee->id) }}" class="modal-btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download PDF
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [5] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No payroll records found', },
        dom: 'rtip',
    });

    $('#payroll-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#dept-filter').on('change', function() {
        table.column(1).search(this.value).draw();
    });
});
</script>

<script>
function openModal() { document.getElementById('payslipModal').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal('payslipModal'); });
</script>
@endpush