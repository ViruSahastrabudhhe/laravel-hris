@extends('layouts.employee')

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div>
            <h2>Welcome back, {{ auth()->user()->name }}!</h2>
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

{{-- Quick Actions --}}
<div class="qa-top-grid">
    <a href="{{ route('my_payslips.index') }}" class="qa-card" style="text-decoration:none">
        <div class="qa-icon" style="background:#f0effe">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <span class="qa-label">View Payslip</span>
        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @if($isJobOrder)
    <a href="{{ route('my_trainings.index') }}" class="qa-card" style="text-decoration:none">
        <div class="qa-icon" style="background:#f0effe">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </div>
        <span class="qa-label">My Trainings</span>
        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @else
    <a href="{{ route('my_leaves.index') }}" class="qa-card" style="text-decoration:none">
        <div class="qa-icon" style="background:#fefce8">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <span class="qa-label">File Leave</span>
        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @endif
    <a href="{{ route('my_attendances.index') }}" class="qa-card" style="text-decoration:none">
        <div class="qa-icon" style="background:#fdf0ef">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <span class="qa-label">View Attendance</span>
        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    <a href="{{ route('my_profile.index') }}" class="qa-card" style="text-decoration:none">
        <div class="qa-icon" style="background:#e8f9ef">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <span class="qa-label">My Profile</span>
        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Basic Pay</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">
            ₱{{ number_format(optional($latestPayslip)->total_earnings ?? 0, 2) }}
        </p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Current period</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Net Pay</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">
            ₱{{ number_format(optional($latestPayslip)->net_pay ?? 0, 2) }}
        </p>

        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">After deductions</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">{{ $isJobOrder ? 'Attendance Rate' : 'Leave Credits' }}</p>
            <div class="stat-icon-wrap" style="background:{{ $isJobOrder ? '#fdf0ef' : '#fefce8' }}">
                @if($isJobOrder)
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                @else
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                @endif
            </div>
        </div>
        <p class="stat-value">{{ $isJobOrder ? ($attendanceRate ?? '—') : ($leaveCredits ?? '—') }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:{{ $isJobOrder ? '#8e1e18' : '#f59e0b' }}"></span>
            <p class="stat-sub">{{ $isJobOrder ? 'This month' : 'Available days' }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Attendance</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $attendanceRate ?? '—' }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">This month</p>
        </div>
    </div>

</div>

{{-- Payslip History Table --}}
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">My Payslips</p>
            <p class="table-sub">Recent payroll history</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('my_payslips.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View All
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Basic Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Pay Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayslips ?? [] as $p)
                <tr>
                    <td style="font-weight:600;color:#0b044d;font-size:13px">{{ $p->month }}/{{ $p->year }}</td>
                    <td style="font-size:13px;color:#0b044d">₱{{ number_format($p->total_earnings, 2) }}</td>
                    <td style="font-size:13px;color:#8e1e18">₱{{ number_format($p->total_deductions, 2) }}</td>
                    <td class="net-pay">₱{{ number_format($p->net_pay, 2) }}</td>
                    <td style="font-size:12.5px;color:#6b6a8a">{{ $p->updated_at->format('M d, Y') }}</td>
                    <td>
                        @if($p->status === 'Processed')
                            <span class="badge-status processed">Processed</span>
                        @else
                            <span class="badge-status pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No payslip records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Bottom Row --}}
<div class="bottom-row">

    @if($isJobOrder)
    {{-- Job Order: Recent Trainings --}}
    <div class="table-section mb-0">
        <div class="table-header">
            <div>
                <p class="table-title">My Trainings</p>
                <p class="table-sub">Recent training programs</p>
            </div>
            <a href="{{ route('my_trainings.index') }}" class="btn-export">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="payroll-table">
                <thead>
                    <tr><th>Program</th><th>Type</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recentTrainings ?? [] as $t)
                    <tr>
                        <td style="font-size:13px;font-weight:600;color:#0b044d">{{ optional($t->training)->program_title ?? '—' }}</td>
                        <td style="font-size:12px;color:#6b6a8a">{{ optional($t->training)->type ?? '—' }}</td>
                        <td>
                            @php $ts = $t->status ?? ''; @endphp
                            @if($ts === \App\Enums\EmployeeTrainingStatus::Completed->value)
                                <span class="badge-status processed">Completed</span>
                            @elseif($ts === \App\Enums\EmployeeTrainingStatus::Enrolled->value)
                                <span class="badge-status pending">Enrolled</span>
                            @else
                                <span class="badge-status on-hold">{{ $ts ?: '—' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No training records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Job Order: Training Summary sidebar --}}
    <div class="side-col">
        <div class="stat-card no-margin" style="margin-top:0">
            <p class="stat-label" style="margin-bottom:12px">Training Summary</p>
            @php
                $totalT     = ($recentTrainings ?? collect())->count();
                $completedT = ($recentTrainings ?? collect())->where('status', \App\Enums\EmployeeTrainingStatus::Completed->value)->count();
                $enrolledT  = ($recentTrainings ?? collect())->where('status', \App\Enums\EmployeeTrainingStatus::Enrolled->value)->count();
                $items = [
                    ['label' => 'Total Enrolled',  'value' => $totalT,     'color' => '#0b044d'],
                    ['label' => 'Completed',        'value' => $completedT, 'color' => '#15803d'],
                    ['label' => 'In Progress',      'value' => $enrolledT,  'color' => '#d9bb00'],
                ];
            @endphp
            @foreach($items as $item)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0effe">
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $item['color'] }};display:inline-block;flex-shrink:0"></span>
                    <span style="font-size:12.5px;font-weight:600;color:#0b044d">{{ $item['label'] }}</span>
                </div>
                <span style="font-size:14px;font-weight:800;color:{{ $item['color'] }}">{{ $item['value'] }}</span>
            </div>
            @endforeach
            <a href="{{ route('my_trainings.index') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:14px;padding:8px;border-radius:8px;background:#f0effe;color:#0b044d;font-size:12px;font-weight:700;text-decoration:none;transition:background 0.2s" onmouseover="this.style.background='#e4e3f8'" onmouseout="this.style.background='#f0effe'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                View All Trainings
            </a>
        </div>
    </div>

    @else
    {{-- Regular: Recent Leave Requests --}}
    <div class="table-section mb-0">
        <div class="table-header">
            <div>
                <p class="table-title">Leave Requests</p>
                <p class="table-sub">Recent submissions</p>
            </div>
            <a href="{{ route('my_leaves.index') }}" class="btn-export">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="payroll-table">
                <thead>
                    <tr><th>Type</th><th>Date Filed</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recentLeaves ?? [] as $l)
                    <tr>
                        <td style="font-size:13px;font-weight:600;color:#0b044d">{{ optional($l->leaveType)->name ?? '—' }}</td>
                        <td style="font-size:12.5px;color:#6b6a8a">{{ optional($l->created_at)->format('M d, Y') }}</td>
                        <td>
                            @if($l->leave_status === 'Approved')
                                <span class="badge-status processed">Approved</span>
                            @elseif($l->leave_status === 'Declined')
                                <span class="badge-status on-hold">Declined</span>
                            @else
                                <span class="badge-status pending">{{ $l->leave_status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Regular: Leave Balance sidebar --}}
    <div class="side-col">
        <div class="stat-card no-margin" style="margin-top:0">
            <p class="stat-label" style="margin-bottom:12px">Leave Balance</p>
            @php
            $leaveTypes = [
                ['label' => 'Vacation Leave', 'balance' => $vacationLeave ?? 0, 'max' => 20, 'color' => '#0b044d'],
                ['label' => 'Sick Leave',     'balance' => $sickLeave ?? 0,     'max' => 20, 'color' => '#8e1e18'],
            ];
            @endphp
            @foreach($leaveTypes as $lt)
            <div style="margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                    <span style="font-weight:600;color:#0b044d">{{ $lt['label'] }}</span>
                    <span style="color:#9999bb">{{ $lt['balance'] }} days</span>
                </div>
                <div style="height:6px;background:#f0effe;border-radius:99px;overflow:hidden">
                    <div style="height:100%;width:{{ min(($lt['balance'] / $lt['max']) * 100, 100) }}%;background:{{ $lt['color'] }};border-radius:99px"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
