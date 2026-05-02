@extends('layouts.admin')

@php
    $totalEmployees = 0;
    $activeEmployees = 0;
    $inactiveEmployees = 0;
    $regularEmployees = 0;
    
    foreach ($employees as $employee) {
        if ($employee->is_active) {
            $activeEmployees++;
        } else {
            $inactiveEmployees++;
        }
        if ($employee->employment_type === \App\Enums\EmploymentType::Regular->value) {
            $regularEmployees++;
        }
        $totalEmployees++;
    }

    $totalDepartments = $departments->count();
    $activeDepartments = $departments->where('is_active', true)->count();
    $inactiveDepartments = $departments->where('is_active', false)->count();
    $totalEmployeesInDepts = $employees->count();

    $totalPositions = $positions->count();
    $filledPositions = $employees->pluck('position_id')->filter()->unique()->count();
    $vacantPositions = max(0, $totalPositions - $filledPositions);
@endphp

@push('styles')
    <style>
        .search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-wrap svg {
            position: absolute;
            left: 10px;
            pointer-events: none;
        }
        .search-input {
            height: 34px;
            padding: 0 10px 0 30px;
            border: 1.5px solid #e4e3f0;
            border-radius: 8px;
            font-size: 12.5px;
            font-family: 'Poppins', sans-serif;
            color: #0b044d;
            background: #fafafe;
            outline: none;
            width: 180px;
            transition: border-color 0.2s;
        }
        .search-input:focus { border-color: #0b044d; }

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
<div id="stats-employees" class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalEmployees }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Active employees</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #15803d">
        <div class="stat-top">
            <p class="stat-label">Active</p>
            <div class="stat-icon-wrap" style="background: rgba(21, 128, 61, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $activeEmployees }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Currently active</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #8e1e18">
        <div class="stat-top">
            <p class="stat-label">Inactive</p>
            <div class="stat-icon-wrap" style="background: rgba(142, 30, 24, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $inactiveEmployees }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Deactivated accounts</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #d9bb00">
        <div class="stat-top">
            <p class="stat-label">Regular</p>
            <div class="stat-icon-wrap" style="background: rgba(217, 187, 0, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $regularEmployees }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Regular employees</p>
        </div>
    </div>
</div>

<div id="stats-departments" class="stats-grid stats-grid-4" style="display:none">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Departments</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalDepartments }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Department records</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #15803d">
        <div class="stat-top">
            <p class="stat-label">Active Departments</p>
            <div class="stat-icon-wrap" style="background: rgba(21, 128, 61, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $activeDepartments }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Open departments</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #8e1e18">
        <div class="stat-top">
            <p class="stat-label">Inactive Departments</p>
            <div class="stat-icon-wrap" style="background: rgba(142, 30, 24, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $inactiveDepartments }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Paused departments</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #d9bb00">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background: rgba(217, 187, 0, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $totalEmployeesInDepts }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Assigned employees</p>
        </div>
    </div>
</div>

<div id="stats-positions" class="stats-grid stats-grid-4" style="display:none">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Positions</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalPositions }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Available roles</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #15803d">
        <div class="stat-top">
            <p class="stat-label">Filled Positions</p>
            <div class="stat-icon-wrap" style="background: rgba(21, 128, 61, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $filledPositions }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Assigned roles</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #8e1e18">
        <div class="stat-top">
            <p class="stat-label">Vacant Positions</p>
            <div class="stat-icon-wrap" style="background: rgba(142, 30, 24, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $vacantPositions }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Open positions</p>
        </div>
    </div>
    <div class="stat-card" style="--accent-color: #d9bb00">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background: rgba(217, 187, 0, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $totalEmployees }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Employees assigned</p>
        </div>
    </div>
</div>

<div class="view-tabs">
    <button class="view-tab active" onclick="switchView('employees', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Employees
    </button>
    <button class="view-tab" onclick="switchView('departments', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Departments
    </button>
    <button class="view-tab" onclick="switchView('positions', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Positions
    </button>
</div>

<div id="view-employees" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Employee Directory</p>
                <p class="table-sub">All active government personnel</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
                <div class="search-wrap">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="employee-search" placeholder="Search employees..." class="search-input">
                </div>
                <select class="filter-select" id="dept-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="position-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->title }}">{{ $position->title }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <a href="{{ route('employees.archive') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    View Archive
                </a>
                <a href="{{ route('employees.create') }}" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Employee
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="attendance-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Employment Type</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($employees as $employee)
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
                        <td><span class="position-cell">{{ $employee->position->title }}</span></td>
                        <td><span class="dept-tag">{{ $employee->department->name }}</span></td>
                        <td>
                            <span class="dept-tag" style="background:{{ $employee->employment_type === \App\Enums\EmploymentType::Regular->value ? '#e8f9ef' : '#fefce8' }};color:{{ $employee->employment_type === \App\Enums\EmploymentType::Regular->value ? '#15803d' : '#a16207' }};border-color:{{ $employee->employment_type === \App\Enums\EmploymentType::Regular->value ? '#bbf7d0' : '#fde68a' }}">
                                {{ $employee->employment_type }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($employee->created_at)->format('M d, Y') }}</td>
                        <td>
                            @if($employee->is_active)
                                <span class="badge-status processed" data-order="0">Active</span>
                            @else
                                <span class="badge-status on-hold" data-order="1">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('employees.show', $employee) }}" class="btn-view">
                                    <svg width="12" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn-edit">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method='post' style="display:inline" onsubmit="return confirm('Archive this employee?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-outline" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @if ($employee->is_active)
                                <form action="{{ route('employees.deactivate', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this employee?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-danger" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('employees.activate', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Activate this employee?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($employees, 'hasPages') && $employees->hasPages())
        <div class="table-footer">
            <span>Showing <strong>{{ $employees->firstItem() }}–{{ $employees->lastItem() }}</strong> of <strong>{{ $employees->total() }}</strong> employees</span>
            <div class="pagination">
                @if($employees->onFirstPage())
                    <button class="page-btn" disabled>‹</button>
                @else
                    <a href="{{ $employees->previousPageUrl() }}" class="page-btn">‹</a>
                @endif

                @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $employees->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($employees->hasMorePages())
                    <a href="{{ $employees->nextPageUrl() }}" class="page-btn">›</a>
                @else
                    <button class="page-btn" disabled>›</button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div id="view-departments" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Departments</p>
                <p class="table-sub">Manage organizational departments</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
                <div class="search-wrap">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="dept-search" placeholder="Search departments..." class="search-input">
                </div>
                <button onclick="openDeptCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Department
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="dept-table">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Code</th>
                        <th>Head</th>
                        <th>Status</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($departments as $department)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($department->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <span style="font-size:13px;font-weight:600;color:#0b044d">{{ $department->name }}</span>
                            </div>
                        </td>
                        <td><span class="dept-tag">{{ $department->department_code }}</span></td>
                        <td><span style="font-size:12.5px;color:#5a5888">{{ $department->department_head ?? 'Not assigned' }}</span></td>
                        <td>
                            <span class="dept-tag" style="background:{{ $department->is_active ? '#d1fae5' : '#fee2e2' }};color:{{ $department->is_active ? '#065f46' : '#991b1b' }}">
                                {{ $department->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @php
                            $employeeCountInDept = $employees->where('department_id', $department->id)->count();
                        @endphp
                        <td>
                            <span class="dept-tag" style="background:#f0effe;color:#0b044d">
                                {{ $employeeCountInDept }} employees
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button onclick="openDeptEditModal({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ addslashes($department->department_code) }}', '{{ addslashes($department->department_head ?? '') }}', '{{ addslashes($department->description ?? '') }}', {{ $department->is_active ? 'true' : 'false' }})" class="btn-edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('departments.destroy', $department) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this department?')">
                                    @csrf   
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <p style="font-size:14px;color:#9999bb;margin-top:12px">No departments found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="view-positions" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Position Management</p>
                <p class="table-sub">Manage job positions and salary grades</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
                <div class="search-wrap">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="position-search" placeholder="Search positions..." class="search-input">
                </div>
                <button onclick="openPosCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Position
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="pos-table">
                <thead>
                    <tr>
                        <th>Position Title</th>
                        <th>Status</th>
                        <th>Vacancy</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($positions as $position)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($position->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                </div>
                                <span style="font-size:13px;font-weight:600;color:#0b044d">{{ $position->title }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($position->is_active)
                                <span class="badge-status processed" data-order="0">Active</span>
                            @else
                                <span class="badge-status on-hold" data-order="1">Inactive</span>
                            @endif
                        </td>
                        @php
                            $employeeCountInPos = $employees->where('position_id', $position->id)->count();
                        @endphp
                        <td>
                            @if ($employeeCountInPos >= $position->total_employees)
                                <span class="dept-tag" style="background: #fefce8; color: #a16207; border-color: #fde68a; }}">
                                    {{ \App\Enums\PositionStatus::Closed->value }}
                                </span>
                            @else
                                <span class="dept-tag" style="background: #e8f9ef; color: #15803d; border-color: #bbf7d0;">
                                    {{ \App\Enums\PositionStatus::Hiring->value }}
                                </span>
                            @endif
                        </td>
                        @php
                            $employeeCountInPos = $employees->where('position_id', $position->id)->count();
                        @endphp
                        <td>
                            <span class="dept-tag" style="background:#f0effe;color:#0b044d">
                                {{ $employeeCountInPos }} / {{ $position->total_employees }} {{ $employeeCountInPos == 1 ? ' employee' : ' employees' }}
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button onclick="openPosEditModal({{ $position->id }}, '{{ addslashes($position->title) }}', {{ $position->total_employees }}, {{ $position->is_active ? 'true' : 'false' }})" class="btn-edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('positions.destroy', $position) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this position?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <p style="font-size:14px;color:#9999bb;margin-top:12px">No positions found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Department Create Modal --}}
<div class="modal-overlay" id="dept-create-modal" style="display:none" onclick="closeDeptCreateModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">DEPARTMENTS</span>
                <h3 class="modal-title">Add New Department</h3>
            </div>
            <button class="modal-close" onclick="closeDeptCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Department Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="name" placeholder="e.g. Human Resources" required>
                </div>
                <div class="form-field">
                    <label>Department Code <span style="color:#dc2626">*</span></label>
                    <input type="text" name="department_code" placeholder="e.g. OM" required>
                </div>
                <div class="form-field">
                    <label>Department Head</label>
                    <input type="text" name="department_head" placeholder="e.g. John Smith">
                </div>
                <div class="form-field">
                    <label>Description</label>
                    <textarea name="description" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Brief description"></textarea>
                </div>
                <div class="form-field">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto">
                        <span>Active Department</span>
                    </label>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeDeptCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Department Edit Modal --}}
<div class="modal-overlay" id="dept-edit-modal" style="display:none" onclick="closeDeptEditModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">DEPARTMENTS</span>
                <h3 class="modal-title">Edit Department</h3>
            </div>
            <button class="modal-close" onclick="closeDeptEditModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="dept-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Department Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="name" id="dept-edit-name" required>
                </div>
                <div class="form-field">
                    <label>Department Code <span style="color:#dc2626">*</span></label>
                    <input type="text" name="department_code" id="dept-edit-code" required>
                </div>
                <div class="form-field">
                    <label>Department Head</label>
                    <input type="text" name="department_head" id="dept-edit-head">
                </div>
                <div class="form-field">
                    <label>Description</label>
                    <textarea name="description" id="dept-edit-desc" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical"></textarea>
                </div>
                <div class="form-field">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="is_active" id="dept-edit-active" value="1" style="width:auto">
                        <span>Active Department</span>
                    </label>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeDeptEditModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Department
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Position Create Modal --}}
<div class="modal-overlay" id="pos-create-modal" style="display:none" onclick="closePosCreateModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">POSITIONS</span>
                <h3 class="modal-title">Add New Position</h3>
            </div>
            <button class="modal-close" onclick="closePosCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Position Title <span style="color:#dc2626">*</span></label>
                    <input type="text" name="title" placeholder="e.g. Administrative Officer" required>
                </div>
                <div class="form-field">
                    <label id="position_status">Status <span style="color:#dc2626">*</span></label>
                    <select name="status" id="position_status" required>
                        <option value="">Select status</option>
                        @foreach (\App\Enums\PositionStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Total Employees <span style="color:#dc2626">*</span></label>
                    <input type="number" name="total_employees" min="1" placeholder="e.g. 5" required>
                </div>
                <div class="form-field">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto">
                        <span>Active Position</span>
                    </label>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closePosCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Position
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Position Edit Modal --}}
<div class="modal-overlay" id="pos-edit-modal" style="display:none" onclick="closePosEditModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">POSITIONS</span>
                <h3 class="modal-title">Edit Position</h3>
            </div>
            <button class="modal-close" onclick="closePosEditModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="pos-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Position Title <span style="color:#dc2626">*</span></label>
                    <input type="text" name="title" id="pos-edit-title" required>
                </div>
                <div class="form-field">
                    <label>Total Employees <span style="color:#dc2626">*</span></label>
                    <input type="number" name="total_employees" id="pos-edit-total" min="1" required>
                </div>
                <div class="form-field">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="is_active" id="pos-edit-active" value="1" style="width:auto">
                        <span>Active Position</span>
                    </label>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closePosEditModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchView(viewId, btn) {
        if (btn.classList.contains('active')) {
            return;
        }

        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.view-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('view-' + viewId).classList.add('active');
        btn.classList.add('active');

        document.getElementById('stats-employees').style.display  = viewId === 'employees'   ? 'grid' : 'none';
        document.getElementById('stats-departments').style.display = viewId === 'departments' ? 'grid' : 'none';
        document.getElementById('stats-positions').style.display   = viewId === 'positions'   ? 'grid' : 'none';
    }

    $(function () {
        function escapeRegex(value) {
            return value.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        }

        if ($('#attendance-table').length) {
            const employeeTable = $('#attendance-table').DataTable({
                columnDefs: [{ orderable: false, targets: [0, 6] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No employees found' },
                dom: 'rtip',
            });

            $('#employee-search').on('keyup', function() {
                employeeTable.search(this.value).draw();
            });

            $('#dept-filter').on('change', function() {
                const value = $(this).val();
                employeeTable.column(2).search(value ? '^' + escapeRegex(value) + '$' : '', true, false).draw();
            });

            $('#position-filter').on('change', function() {
                const value = $(this).val();
                employeeTable.column(1).search(value ? '^' + escapeRegex(value) + '$' : '', true, false).draw();
            });

            $('#status-filter').on('change', function() {
                const value = $(this).val();
                employeeTable.column(5).search(value ? '^' + escapeRegex(value) + '$' : '', true, false).draw();
            });
        }

        if ($('#dept-table').length) {
            const deptTable = $('#dept-table').DataTable({
                columnDefs: [{ orderable: false, targets: [5] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No departments found' },
                dom: 'rtip',
            });

            $('#dept-search').on('keyup', function() {
                deptTable.search(this.value).draw();
            });
        }

        if ($('#pos-table').length) {
            const posTable = $('#pos-table').DataTable({
                columnDefs: [{ orderable: false, targets: [2] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No positions found' },
                dom: 'rtip',
            });

            $('#position-search').on('keyup', function() {
                posTable.search(this.value).draw();
            });
        }
    });
</script>

<script>
    // Department modals
    function openDeptCreateModal() { document.getElementById('dept-create-modal').style.display = 'flex'; }
    function closeDeptCreateModal() { document.getElementById('dept-create-modal').style.display = 'none'; }

    function openDeptEditModal(id, name, code, head, desc, isActive) {
        var url = "{{ route('departments.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('dept-edit-form').action = url;
        document.getElementById('dept-edit-name').value = name;
        document.getElementById('dept-edit-code').value = code;
        document.getElementById('dept-edit-head').value = head;
        document.getElementById('dept-edit-desc').value = desc;
        document.getElementById('dept-edit-active').checked = isActive;
        document.getElementById('dept-edit-modal').style.display = 'flex';
    }
    function closeDeptEditModal() { document.getElementById('dept-edit-modal').style.display = 'none'; }

    // Position modals
    function openPosCreateModal() { document.getElementById('pos-create-modal').style.display = 'flex'; }
    function closePosCreateModal() { document.getElementById('pos-create-modal').style.display = 'none'; }

    function openPosEditModal(id, title, total, isActive) {
        var url = "{{ route('positions.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('pos-edit-form').action = url;
        document.getElementById('pos-edit-title').value = title;
        document.getElementById('pos-edit-total').value = total;
        document.getElementById('pos-edit-active').checked = isActive;
        document.getElementById('pos-edit-modal').style.display = 'flex';
    }
    function closePosEditModal() { document.getElementById('pos-edit-modal').style.display = 'none'; }

</script>
@endpush