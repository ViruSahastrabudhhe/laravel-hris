@extends('layouts.employee')

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
            <h2>My Payslips</h2>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            {{ now()->format('F Y') }} Payroll Active
        </span>
        <span class="banner-badge outline">Next Pay: {{ now()->endOfMonth()->format('M d') }}</span>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Latest Net Pay</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format(optional($latestPayslip)->net_pay ?? 0, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">{{ $latestPayslip ? $latestPayslip->month . '/' . $latestPayslip->year : 'No record' }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Basic Pay</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format(optional($latestPayslip)->total_earnings ?? 0, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Latest period</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Deductions</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format(optional($latestPayslip)->total_deductions ?? 0, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Latest period</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Payslips</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalPayslips }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">All records</p>
        </div>
    </div>

</div>

{{-- Payslip History Table --}}
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Payslip History</p>
            <p class="table-sub">Your payroll records</p>
        </div>
        <form method="GET" action="{{ route('my_payslips.index') }}" class="table-actions">
            <div class="att-date-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <input type="month" name="start_month" value="{{ $startMonth ?? '' }}" class="att-date-input" placeholder="Start month">
            </div>
            <span style="color:#9999bb;font-size:12px">to</span>
            <div class="att-date-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <input type="month" name="end_month" value="{{ $endMonth ?? '' }}" class="att-date-input" placeholder="End month">
            </div>
            <button type="submit" class="modal-btn-primary" style="padding:7px 16px;font-size:12.5px">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filter
            </button>
            @if(($startMonth ?? null) || ($endMonth ?? null))
            <a href="{{ route('my_payslips.index') }}" class="btn-export">Clear</a>
            @endif
            @if($latestPayslip)
            <button type="button" class="btn-export" onclick="openPayslipModal({{ $latestPayslip->id }})">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View Latest
            </button>
            @endif
        </form>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Basic Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payslips as $p)
                <tr>
                    <td style="font-weight:600;color:#0b044d;font-size:13px">{{ $p->month }}/{{ $p->year }}</td>
                    <td style="font-size:13px;color:#0b044d">₱{{ number_format($p->total_earnings, 2) }}</td>
                    <td style="font-size:13px;color:#8e1e18">₱{{ number_format($p->total_deductions, 2) }}</td>
                    <td class="net-pay">₱{{ number_format($p->net_pay, 2) }}</td>
                    <td>
                        @if($p->status === 'Processed')
                            <span class="badge-status processed">Processed</span>
                        @else
                            <span class="badge-status pending">Pending</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-view" onclick="openPayslipModal({{ $p->id }})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#9999bb;font-size:13px">No payslip records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Payslip Detail Modal --}}
<div class="modal-overlay" id="payslipModal" style="display:none" onclick="closeModal('payslipModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow" id="ps-eyebrow">PAYSLIP</span>
                <h3 class="modal-title">{{ auth()->user()->name }}</h3>
                <p class="modal-sub" id="ps-sub">Loading...</p>
            </div>
            <button class="modal-close" onclick="closeModal('payslipModal')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" id="ps-body">
            <div style="text-align:center;padding:20px;color:#9999bb;font-size:13px">Loading...</div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('payslipModal')">Close</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openPayslipModal(id) {
        document.getElementById('ps-body').innerHTML = '<div style="text-align:center;padding:20px;color:#9999bb;font-size:13px">Loading...</div>';
        document.getElementById('payslipModal').style.display = 'flex';

        fetch(`/employee/my_payslips/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('ps-eyebrow').textContent = 'PAYSLIP · ' + data.period;
                document.getElementById('ps-sub').textContent = data.status;

                let earningsHtml = '';
                data.earnings.forEach(e => {
                    earningsHtml += `<div class="modal-row"><span>${e.name}</span><strong>₱${parseFloat(e.amount).toLocaleString('en-PH', {minimumFractionDigits:2})}</strong></div>`;
                });
                if (!data.earnings.length) {
                    earningsHtml = `<div class="modal-row"><span>Basic Pay</span><strong>₱${parseFloat(data.total_earnings).toLocaleString('en-PH', {minimumFractionDigits:2})}</strong></div>`;
                }

                let deductionsHtml = '';
                data.deductions.forEach(d => {
                    deductionsHtml += `<div class="modal-row"><span>${d.name}</span><span class="modal-deduct">₱${parseFloat(d.amount).toLocaleString('en-PH', {minimumFractionDigits:2})}</span></div>`;
                });

                document.getElementById('ps-body').innerHTML =
                    '<div class="modal-section-label">EARNINGS</div>' + earningsHtml +
                    '<div class="modal-section-label" style="margin-top:16px">DEDUCTIONS</div>' + deductionsHtml +
                    `<div class="modal-row total"><span>Total Deductions</span><span class="modal-deduct">₱${parseFloat(data.total_deductions).toLocaleString('en-PH', {minimumFractionDigits:2})}</span></div>` +
                    `<div class="modal-net-row"><span>NET PAY</span><strong>₱${parseFloat(data.net_pay).toLocaleString('en-PH', {minimumFractionDigits:2})}</strong></div>`;
            })
            .catch(() => {
                document.getElementById('ps-body').innerHTML = '<div style="text-align:center;padding:20px;color:#8e1e18;font-size:13px">Failed to load payslip details.</div>';
            });
    }
</script>
@endpush
