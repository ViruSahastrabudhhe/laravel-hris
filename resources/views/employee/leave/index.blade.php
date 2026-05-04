@extends('layouts.employee')

@php
    use App\Enums\LeaveStatus;
    use Carbon\Carbon;

    $totalLeaves = 0;
@endphp

@push('styles')
    <style>
        .credits-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .credit-card { background:#fff; border-radius:14px; border:1.5px solid #e5e4f0; padding:22px; }
        .credit-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px; }
        .credit-header label { font-size:12px; color:#9999bb; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }
        .credit-header h2 { font-size:28px; font-weight:800; margin:0; }
        .credit-header p { font-size:12px; color:#9999bb; margin-top:2px; }
        .credit-stats { text-align:right; }
        .credit-stats p { font-size:11.5px; color:#9999bb; margin-bottom:4px; }
        .credit-stats strong { color:#0b044d; }
        .progress-bar { height:8px; background:#f0effe; border-radius:4px; overflow:hidden; }
        .progress-fill { height:100%; border-radius:4px; transition:width 0.4s; }
        .progress-labels { display:flex; justify-content:space-between; margin-top:6px; }
        .progress-labels span { font-size:11px; color:#9999bb; }
        .dept-tag { font-size:10px; font-weight:600; padding:3px 8px; border-radius:20px; background:#f0effe; color:#6b3fa0; }
        .deduction { color:#dc2626; font-weight:600; }
        .benefits-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
        .benefits-grid .stat-card { margin-bottom:0; }
        .tabs { display:flex; gap:4px; margin-bottom:24px; border-bottom:1.5px solid #eceaf8; }
        .tab-btn { background:none; border:none; cursor:pointer; padding:10px 20px; font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:#9999bb; border-bottom:2.5px solid transparent; margin-bottom:-1.5px; transition:all 0.2s; }
        .tab-btn:hover { color:#0b044d; }
        .tab-btn.active { color:#0b044d; border-bottom-color:#0b044d; }

        @media (max-width: 768px) {
            .welcome-banner { flex-direction:column; gap:16px; }
            .banner-left { flex-direction:column; align-items:flex-start; }
            .banner-left p { font-size:11px; }
            .banner-right { flex-wrap:wrap; gap:8px; }
            .stats-grid-4 { grid-template-columns:1fr !important; }
            .table-header { flex-direction:column; gap:12px; }
            .table-actions { flex-direction:column; width:100%; }
            .table-actions select, .table-actions .btn-export { width:100%; }
            .table-wrapper { overflow-x:auto; -webkit-overflow-scrolling:touch; }
            .payroll-table { min-width:800px; }
            .tabs { overflow-x:auto; -webkit-overflow-scrolling:touch; flex-wrap:nowrap; }
            .tab-btn { white-space:nowrap; padding:10px 16px; font-size:12px; }
            .credits-grid { grid-template-columns:1fr; }
            .benefits-grid { grid-template-columns:1fr !important; }
            .form-grid { grid-template-columns:1fr; }
            .modal-box { max-width:calc(100% - 32px); }
            .modal-header { padding:20px 20px 0; }
            .modal-body { padding:16px 20px; }
            .modal-footer { padding:12px 20px 20px; flex-direction:column; }
            .modal-btn-ghost, .modal-btn-primary { width:100%; justify-content:center; }
        }

        @media (max-width: 480px) {
            .banner-icon { width:40px; height:40px; }
            .banner-icon svg { width:18px; height:18px; }
            .welcome-banner h2 { font-size:18px; }
            .stat-card { padding:16px; }
            .stat-value { font-size:24px; }
            .credit-card { padding:16px; }
            .credit-header h2 { font-size:24px; }
            .table-title { font-size:16px; }
            .modal-title { font-size:16px; }
        }
    </style>
@endpush

@section('page-content')
<div class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Leave Filed</p>
            <div class="stat-icon-wrap" style="background:#0b044d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        </div>
        <h2 class="stat-value">6</h2>
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
        <h2 class="stat-value">13</h2>
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
        <h2 class="stat-value">1</h2>
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
        <h2 class="stat-value">21</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">10 VL · 11 SL</p>
        </div>
    </div>
</div>

<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('leave', this)">My Leave Requests</button>
    <button class="tab-btn" onclick="switchTab('credits', this)">Leave Credits</button>
    <button class="tab-btn" onclick="switchTab('benefits', this)">My Benefits</button>
</div>

{{-- Tab Content --}}
<div id="tab-leave" class="tab-content">
    <section class="table-section">
        <div class="table-header">
            <div>
                <h3 class="table-title">My Leave Requests</h3>
                <p class="table-sub">Manage your leave requests</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="leaves-search" placeholder="Search programs..." class="search-input">
                </div>
                <select class="filter-select" id="type-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->leave_type }}">{{ $type->leave_type }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    @foreach($leaveStatuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
                <button onclick="openLeaveCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    File Leave
                </button>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table class="payroll-table" id="leaves-table">
                <thead>
                    <tr>
                        <th>Leave ID</th>
                        <th style="display: none;">Leave Type</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaveRequests as $leave)
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div>
                                    <p class="emp-name" style="font-weight:600;">{{ $leave->leaveType->leave_type }}</p>
                                    <p class="emp-id" style="font-size:12;color:#9999bb;font-weight:500;">LV-{{ Carbon::parse($leave->start_date)->format(config('app.year')) }}-{{ str_pad($leave->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="display: none;">{{ $leave->leaveType->leave_type }}</td>
                        <td>{{ Carbon::parse($leave->start_date)->format(config('app.day_month')) }}</td>
                        <td>{{ Carbon::parse($leave->end_date)->format(config('app.day_month')) }}</td>
                        <td style="font-weight:700;">{{ $leave->leave_duration }} {{ $leave->leave_duration == 1 ? ' day' : ' days' }}</td>
                        <td style="font-size:12.5;color:#5a5888;">{{ $leave->leave_reason }}</td>
                        <td>
                            @if($leave->leave_status === LeaveStatus::Approved->value)
                                <span class="badge-status processed">Approved</span>
                            @elseif($leave->leave_status === LeaveStatus::Pending->value)
                                <span class="badge-status pending">Pending</span>
                            @else
                                <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                            @endif
                        </td>
                        <td><button class="btn-view" onclick="openDetailModal()">View</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="tab-credits" class="tab-content hidden">
    <div class="credits-grid">
        <div class="credit-card">
            <div class="credit-header">
                <div>
                    <label>Vacation Leave</label>
                    <h2 style="color:#0b044d">10</h2>
                    <p>days remaining</p>
                </div>
                <div class="credit-stats">
                    <p>Earned: <strong>15</strong></p>
                    <p>Used: <strong style="color:#8e1e18">5</strong></p>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:67%;background:#0b044d"></div>
            </div>
            <div class="progress-labels">
                <span>0</span>
                <span>15 days max</span>
            </div>
        </div>
        <div class="credit-card">
            <div class="credit-header">
                <div>
                    <label>Sick Leave</label>
                    <h2 style="color:#15803d">11</h2>
                    <p>days remaining</p>
                </div>
                <div class="credit-stats">
                    <p>Earned: <strong>15</strong></p>
                    <p>Used: <strong style="color:#8e1e18">4</strong></p>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:73%;background:#15803d"></div>
            </div>
            <div class="progress-labels">
                <span>0</span>
                <span>15 days max</span>
            </div>
        </div>
        <div class="credit-card">
            <div class="credit-header">
                <div>
                    <label>Emergency Leave</label>
                    <h2 style="color:#8e1e18">2</h2>
                    <p>days remaining</p>
                </div>
                <div class="credit-stats">
                    <p>Earned: <strong>3</strong></p>
                    <p>Used: <strong style="color:#8e1e18">1</strong></p>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:67%;background:#8e1e18"></div>
            </div>
            <div class="progress-labels">
                <span>0</span>
                <span>3 days max</span>
            </div>
        </div>
        <div class="credit-card">
            <div class="credit-header">
                <div>
                    <label>Special Leave</label>
                    <h2 style="color:#d9bb00">3</h2>
                    <p>days remaining</p>
                </div>
                <div class="credit-stats">
                    <p>Earned: <strong>3</strong></p>
                    <p>Used: <strong style="color:#8e1e18">0</strong></p>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:100%;background:#d9bb00"></div>
            </div>
            <div class="progress-labels">
                <span>0</span>
                <span>3 days max</span>
            </div>
        </div>
    </div>
</div>

<div id="tab-benefits" class="tab-content hidden">
    <div class="benefits-grid">
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">GSIS Premium</p>
                <div class="stat-icon-wrap" style="background:#0b044d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></div>
            </div>
            <h2 class="stat-value">₱3,046</h2>
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
            <h2 class="stat-value">₱850</h2>
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
            <h2 class="stat-value">₱100</h2>
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
            <h2 class="stat-value">₱2,772</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#d9bb00"></span>
                <p class="stat-sub">Monthly deduction</p>
            </div>
        </div>
    </div>
        
    <section class="table-section">
        <div class="table-header">
            <div>
                <h3 class="table-title">Benefits Breakdown — June 2025</h3>
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
                        <td class="deduction">₱3,046</td>
                        <td style="font-weight:600;color:#5a5888;">₱36,552</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">PhilHealth</td>
                        <td><span class="dept-tag">Health Insurance</span></td>
                        <td class="deduction">₱850</td>
                        <td style="font-weight:600;color:#5a5888;">₱10,200</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Pag-IBIG</td>
                        <td><span class="dept-tag">Housing Fund</span></td>
                        <td class="deduction">₱100</td>
                        <td style="font-weight:600;color:#5a5888;">₱1,200</td>
                        <td><span class="badge-status processed">Active</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Withholding Tax</td>
                        <td><span class="dept-tag">Government Tax</span></td>
                        <td class="deduction">₱2,772</td>
                        <td style="font-weight:600;color:#5a5888;">₱33,264</td>
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

{{-- Leave Create Modal --}}
<div class="modal-overlay" id="leave-create-modal" style="display:none" onclick="closeLeaveCreateModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUESTS</span>
                <h2 class="modal-title">File New Leave</h2>
            </div>
            <button class="modal-close" onclick="closeLeaveCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('employee_leaves.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <h3 class="modal-title" style="margin-bottom: 1rem;">For {{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}"> 
                </div>
                <div class="form-field">
                    <label>Leave Type <span style="color:#dc2626">*</span></label>
                    <select name="leave_type_id" required>
                        <option value="">Select leave type</option>
                        @forelse($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
                        @empty
                        @endforelse
                    </select>
                    @if($leaveTypes->isEmpty())
                    <span style="font-size:11.5px;color:#8e1e18">No leave types available. <a href="{{ route('leave_types.create') }}" style="color:#8e1e18;font-weight:600">Add one here.</a></span>
                    @endif
                </div>
                <div class="form-field">
                    <div class="auth-field">
                        <label>Start Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="auth-field">
                        <label>End Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="leave_reason" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for leave" required>{{ old('leave_reason') }}</textarea>
                </div>
                <div class="form-field">
                    <div id="decline_reason_wrap" style="display:none">
                        <div class="auth-field">
                            <label>Decline Reason</label>
                            <textarea name="decline_reason" id="decline_reason_input" rows="2" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for declining">{{ old('decline_reason') }}</textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeLeaveCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    File Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Leave Edit Modal --}}
<div class="modal-overlay" id="leave-edit-modal" style="display:none" onclick="closeLeaveEditModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUESTS</span>
                <h3 class="modal-title">Edit Leave Request</h3>
            </div>
            <button class="modal-close" onclick="closeLeaveEditModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="leave-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <input type="number" name="employee_id" value="{{ $employee->id }}">
                </div>
                <div class="form-field">
                    <label>Leave Type <span style="color:#dc2626">*</span></label>
                    <select name="leave_type_id" id="edit-leave-type" required>
                        <option value="">Select leave type</option>
                        @forelse($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="form-field">
                    <div class="auth-field">
                        <label>Start Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" id="edit-start-date" required>
                    </div>
                    <div class="auth-field">
                        <label>End Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" id="edit-end-date" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="leave_reason" id="edit-leave-reason" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" required></textarea>
                </div>
                <div class="form-field">
                    <label>Status <span style="color:#dc2626">*</span></label>
                    <select name="leave_status" id="edit-leave-status" required>
                        <option value="">Select status</option>
                        @foreach($leaveStatuses as $leaveStatus)
                            <option value="{{ $leaveStatus->name }}">{{ $leaveStatus->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <div id="edit-decline-reason-wrap" style="display:none">
                        <div class="auth-field">
                            <label>Decline Reason</label>
                            <textarea name="decline_reason" id="edit-decline-reason" rows="2" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical"></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeLeaveEditModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Leave Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        const leave_table = $('#leaves-table').DataTable({
            columnDefs: [{ orderable: false, targets: [6] }],
            pageLength: 25,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No leave requests found', },
            dom: 'rtip',
        });    
    
        $('#leaves-search').on('keyup', function() {
            leave_table.search(this.value).draw();
        });

        $('#type-filter').on('change', function() {
            leave_table.column(1).search(this.value).draw();
        });

        $('#status-filter').on('change', function() {
            const val = this.value ? '^' + this.value + '$' : '';
            leave_table.column(6).search(val, true, false).draw();
        });

        $('#scan-search').on('keyup', function() {
            scan_table.search(this.value).draw();
        });

        $('#scan-dept-filter').on('change', function() {
            scan_table.column(2).search(this.value).draw();
        });

        $('#scan-status-filter').on('change', function() {
            scan_table.column(8).search(this.value).draw();
        });

        $('#qr-search').on('keyup', function() {
            qr_table.search(this.value).draw();
        });

        $('#qr-position-filter').on('change', function() {
            qr_table.column(1).search(this.value).draw();
        });

        $('#qr-status-filter').on('change', function() {
            const val = this.value ? '^' + this.value + '$' : '';
            qr_table.column(4).search(val, true, false).draw();
        });
    });
</script>

<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>

<script>
    function openLeaveCreateModal() { document.getElementById('leave-create-modal').style.display = 'flex'; }
    function closeLeaveCreateModal() { document.getElementById('leave-create-modal').style.display = 'none'; }

    function openLeaveEditModal(id, employeeId, leaveTypeId, startDate, endDate, reason, status, declineReason) {
        var url = "{{ route('leave_requests.update', ':id') }}".replace(':id', id);
        document.getElementById('leave-edit-form').action = url;
        document.getElementById('edit-employee').value = employeeId;
        document.getElementById('edit-leave-type').value = leaveTypeId;
        document.getElementById('edit-start-date').value = startDate;
        document.getElementById('edit-end-date').value = endDate;
        document.getElementById('edit-leave-reason').value = reason;
        document.getElementById('edit-leave-status').value = status;
        document.getElementById('edit-decline-reason').value = declineReason || '';
        document.getElementById('edit-decline-reason-wrap').style.display = status === 'Declined' ? 'block' : 'none';
        document.getElementById('leave-edit-modal').style.display = 'flex';
    }
    function closeLeaveEditModal() { document.getElementById('leave-edit-modal').style.display = 'none'; }

    document.getElementById('edit-leave-status').addEventListener('change', function() {
        document.getElementById('edit-decline-reason-wrap').style.display = this.value === 'Declined' ? 'block' : 'none';
    });
</script>
@endpush