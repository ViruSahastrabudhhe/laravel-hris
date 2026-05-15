@extends('layouts.admin')
@php $hideChat = true; @endphp

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

@section('page-content')
    <div class="welcome-banner">
        <div class="banner-left">
            <div class="banner-icon">
                <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <h2>{{ __('common.app_personnel') }}</h2>
                <p>{{ config('app.carbon_date') }}</p>
            </div>
        </div>
        <div class="banner-right">
            <div class="recruit-search-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="banner-search" placeholder="Search..." class="recruit-search" oninput="$('.tab-pane.active table').DataTable().search(this.value).draw()">
            </div>
        </div>
    </div>

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

{{-- Tabs --}}
<div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:1.5px solid #eceaf8;padding-bottom:0">
    <button class="tab-btn active" onclick="switchView('employees', this)">Employees</button>
    <button class="tab-btn" onclick="switchView('departments', this)">Departments</button>
    <button class="tab-btn" onclick="switchView('positions', this)">Positions</button>
</div>

<div id="tab-employees" class="tab-pane active">
    <div class="table-section">
        <div class="table-header" style="flex-direction:column;align-items:stretch;gap:12px">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <p class="table-title">Employee Directory</p>
                    <p class="table-sub">All active government personnel</p>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <select class="filter-select" id="dept-filter" onchange="filterEmployeeTable()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <select class="filter-select" id="status-filter" onchange="filterEmployeeTable()">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <a href="{{ route('employees.archive') }}" class="btn-export">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        View Archive
                    </a>
                    <a class="btn-export" id="import-employee-btn" href="#import">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Import
                    </a>
                    <button type="button" onclick="openCreateEmpModal()" class="modal-btn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Employee
                    </button>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="employee-table">
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
                                    <p class="emp-name">{{ $employee->first_name }} {{ substr($employee->middle_name, 0, 1) . '.' }} {{ $employee->last_name }}</p>
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
                        <td>{{ \Carbon\Carbon::parse($employee->created_at)->format(config('app.day_month')) }}</td>
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
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <button type="button" onclick="openEditEmpModal({{ $employee->id }},'{{ addslashes($employee->first_name) }}','{{ addslashes($employee->last_name) }}','{{ addslashes($employee->middle_name) }}','{{ $employee->gender }}','{{ $employee->date_of_birth }}','{{ addslashes($employee->email) }}','{{ addslashes($employee->phone_number) }}','{{ addslashes($employee->address->address ?? '') }}','{{ addslashes($employee->address->city ?? '') }}','{{ addslashes($employee->address->province ?? '') }}','{{ addslashes($employee->address->country ?? '') }}','{{ $employee->address->zip_code ?? '' }}','{{ $employee->position_id }}','{{ $employee->department_id }}','{{ $employee->employment_type }}','{{ $employee->employeeWorkSchedule->workSchedule->id ?? '' }}','{{ $employee->is_active }}','{{ $employee->salary->salary_grade ?? '' }}','{{ $employee->salary->step ?? '' }}','{{ $employee->salary->salary_type->value ?? '' }}','{{ $employee->salary->amount ?? '' }}')" class="btn-edit">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
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
    </div>
</div>

<div id="tab-departments" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Departments</p>
                <p class="table-sub">Manage organizational departments</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
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

<div id="tab-positions" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Position Management</p>
                <p class="table-sub">Manage job positions and salary grades</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
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

{{-- Edit Employee Modal --}}
<div class="modal-overlay" id="edit-emp-modal" style="display:none" onclick="closeEditEmpModal()">
    <div class="modal-box modal-lg" onclick="event.stopPropagation()" style="width:min(680px,100%)">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEES</span>
                <h3 class="modal-title" id="eemp-title">Edit Employee</h3>
            </div>
            <button class="modal-close" onclick="closeEditEmpModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="pmodal-tabs" id="eemp-tabs">
            <button type="button" class="pmodal-tab active" onclick="eempGoTo(0)">Biographical</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(1)">Address</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(2)">Employment</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(3)">Salary</button>
        </div>

        <form id="eemp-form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">

            <div class="pmodal-body" style="padding:20px 24px">

                {{-- Step 0: Biographical --}}
                <div class="eemp-step" id="eemp-step-0">
                    <p class="form-section-label">BIOGRAPHICAL INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>First Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="first_name" id="eemp-first_name" required>
                        </div>
                        <div class="form-field">
                            <label>Last Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="last_name" id="eemp-last_name" required>
                        </div>
                        <div class="form-field">
                            <label>Middle Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="middle_name" id="eemp-middle_name">
                        </div>
                        <div class="form-field">
                            <label>Gender <span style="color:#dc2626">*</span></label>
                            <select name="gender" id="eemp-gender" required>
                                <option value="">Select gender</option>
                                @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Date of Birth <span style="color:#dc2626">*</span></label>
                            <input type="date" name="date_of_birth" id="eemp-dob" required>
                        </div>
                        <div class="form-field">
                            <label>Email Address <span style="color:#dc2626">*</span></label>
                            <input type="email" name="email" id="eemp-email" required>
                        </div>
                        <div class="form-field">
                            <label>Contact Number <span style="color:#dc2626">*</span></label>
                            <input type="text" name="phone_number" id="eemp-phone" required>
                        </div>
                    </div>
                </div>

                {{-- Step 1: Address --}}
                <div class="eemp-step" id="eemp-step-1" style="display:none">
                    <p class="form-section-label">ADDRESS INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field form-full">
                            <label>Street Address <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[address]" id="eemp-address" required>
                        </div>
                        <div class="form-field">
                            <label>City <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[city]" id="eemp-city" required>
                        </div>
                        <div class="form-field">
                            <label>Province <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[province]" id="eemp-province" required>
                        </div>
                        <div class="form-field">
                            <label>Country <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[country]" id="eemp-country" required>
                        </div>
                        <div class="form-field">
                            <label>Zip Code <span style="color:#dc2626">*</span></label>
                            <input type="number" name="address[zip_code]" id="eemp-zip" required>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Employment --}}
                <div class="eemp-step" id="eemp-step-2" style="display:none">
                    <p class="form-section-label">EMPLOYMENT INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Position <span style="color:#dc2626">*</span></label>
                            <select name="position_id" id="eemp-position" required>
                                <option value="">Select position</option>
                                @foreach($positions as $position)
                                    @php $cnt = $employees->where('position_id',$position->id)->count(); @endphp
                                    <option value="{{ $position->id }}" {{ $cnt >= $position->total_employees ? 'style=color:#9999bb' : '' }}>{{ $position->title }}{{ $cnt >= $position->total_employees ? ' (Closed)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Department <span style="color:#dc2626">*</span></label>
                            <select name="department_id" id="eemp-department" required>
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Employment Type <span style="color:#dc2626">*</span></label>
                            <select name="employment_type" id="eemp-emptype" required>
                                <option value="">Select type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Work Schedule <span style="color:#dc2626">*</span></label>
                            <select name="work_schedule_id" id="eemp-schedule" required>
                                <option value="">Select schedule</option>
                                @foreach($workSchedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Status <span style="color:#dc2626">*</span></label>
                            <select name="is_active" id="eemp-status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Salary --}}
                <div class="eemp-step" id="eemp-step-3" style="display:none">
                    <p class="form-section-label">SALARY INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Salary Type <span style="color:#dc2626">*</span></label>
                            <select name="salary[salary_type]" id="eemp-saltype" required>
                                <option value="">Select type</option>
                                @foreach($salaryTypes as $type)
                                    <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Amount <span style="color:#dc2626">*</span></label>
                            <input type="number" step="0.01" min="0" name="salary[amount]" id="eemp-amount" required>
                        </div>
                        <div class="form-field">
                            <label>Salary Grade</label>
                            <input type="number" name="salary[salary_grade]" id="eemp-grade" min="1">
                        </div>
                        <div class="form-field">
                            <label>Step</label>
                            <input type="number" name="salary[step]" id="eemp-step-val" min="1">
                        </div>
                    </div>
                </div>

            </div>{{-- end pmodal-body --}}

            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" id="eemp-prev" onclick="eempNav(-1)" style="display:none">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back
                </button>
                <button type="button" class="modal-btn-ghost" onclick="closeEditEmpModal()">Cancel</button>
                <button type="button" class="modal-btn-primary" id="eemp-next" onclick="eempNav(1)">
                    Next
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                <button type="submit" class="modal-btn-primary" id="eemp-submit" style="display:none">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Create Employee Modal --}}
<div class="modal-overlay" id="create-emp-modal" style="display:none" onclick="closeCreateEmpModal()">
    <div class="modal-box modal-lg" onclick="event.stopPropagation()" style="width:min(680px,100%)">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEES</span>
                <h3 class="modal-title">Add New Employee</h3>
            </div>
            <button class="modal-close" onclick="closeCreateEmpModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Step tabs --}}
        <div class="pmodal-tabs" id="cemp-tabs">
            <button type="button" class="pmodal-tab active" data-step="0" onclick="cempGoTo(0)">Biographical</button>
            <button type="button" class="pmodal-tab" data-step="1" onclick="cempGoTo(1)">Address</button>
            <button type="button" class="pmodal-tab" data-step="2" onclick="cempGoTo(2)">Employment</button>
            <button type="button" class="pmodal-tab" data-step="3" onclick="cempGoTo(3)">Salary</button>
            <button type="button" class="pmodal-tab" data-step="4" onclick="cempGoTo(4)">Account</button>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" id="cemp-form">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">

            <div class="pmodal-body" style="padding:20px 24px">

                {{-- Step 0: Biographical --}}
                <div class="cemp-step" id="cemp-step-0">
                    <p class="form-section-label">BIOGRAPHICAL INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>First Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
                        </div>
                        <div class="form-field">
                            <label>Last Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" required>
                        </div>
                        <div class="form-field">
                            <label>Gender <span style="color:#dc2626">*</span></label>
                            <select name="gender" required>
                                <option value="">Select gender</option>
                                <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                                <option value="Prefer not to say" {{ old('gender')=='Prefer not to say'?'selected':'' }}>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Date of Birth <span style="color:#dc2626">*</span></label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        </div>
                        <div class="form-field">
                            <label>Email Address <span style="color:#dc2626">*</span></label>
                            <input type="email" name="email" id="cemp-email" value="{{ old('email') }}" placeholder="e.g. juan@lgu.gov.ph" required>
                            <span id="cemp-email-error" style="color:#dc2626;font-size:11px;display:none">This email is already in use.</span>
                        </div>
                        <div class="form-field">
                            <label>Contact Number <span style="color:#dc2626">*</span></label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g. 09XX-XXX-XXXX" required>
                        </div>
                    </div>
                </div>

                {{-- Step 1: Address --}}
                <div class="cemp-step" id="cemp-step-1" style="display:none">
                    <p class="form-section-label">ADDRESS INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field form-full">
                            <label>Street Address <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[address]" value="{{ old('address.address') }}" placeholder="e.g. 123 Main Street" required>
                        </div>
                        <div class="form-field">
                            <label>City <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[city]" value="{{ old('address.city') }}" placeholder="e.g. Pagsanjan" required>
                        </div>
                        <div class="form-field">
                            <label>Province <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[province]" value="{{ old('address.province') }}" placeholder="e.g. Laguna" required>
                        </div>
                        <div class="form-field">
                            <label>Country <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[country]" value="{{ old('address.country','Philippines') }}" required>
                        </div>
                        <div class="form-field">
                            <label>Zip Code <span style="color:#dc2626">*</span></label>
                            <input type="number" name="address[zip_code]" value="{{ old('address.zip_code') }}" placeholder="e.g. 4008" required>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Employment --}}
                <div class="cemp-step" id="cemp-step-2" style="display:none">
                    <p class="form-section-label">EMPLOYMENT INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Position <span style="color:#dc2626">*</span></label>
                            <select name="position_id" required>
                                <option value="">Select position</option>
                                @foreach($positions as $position)
                                    @if($position->status === \App\Enums\PositionStatus::Closed->value)
                                        <option value="{{ $position->id }}" disabled style="color:#9999bb">{{ $position->title }} (Closed)</option>
                                    @else
                                        <option value="{{ $position->id }}" {{ old('position_id')==$position->id?'selected':'' }}>{{ $position->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Department <span style="color:#dc2626">*</span></label>
                            <select name="department_id" required>
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id')==$department->id?'selected':'' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Employment Type <span style="color:#dc2626">*</span></label>
                            <select name="employment_type" required>
                                <option value="">Select type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type->value }}" {{ old('employment_type')==$type->value?'selected':'' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Work Schedule <span style="color:#dc2626">*</span></label>
                            <select name="work_schedule_id" required>
                                <option value="">Select schedule</option>
                                @foreach($workSchedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('work_schedule_id')==$schedule->id?'selected':'' }}>{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Status <span style="color:#dc2626">*</span></label>
                            <select name="is_active" required>
                                <option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Active</option>
                                <option value="0" {{ old('is_active')=='0'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Salary --}}
                <div class="cemp-step" id="cemp-step-3" style="display:none">
                    <p class="form-section-label">SALARY INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Salary Type <span style="color:#dc2626">*</span></label>
                            <select name="salary[salary_type]" required>
                                <option value="">Select type</option>
                                @foreach($salaryTypes as $type)
                                    <option value="{{ $type->value }}" {{ old('salary.salary_type')==$type->value?'selected':'' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Amount <span style="color:#dc2626">*</span></label>
                            <input type="number" step="0.01" name="salary[amount]" value="{{ old('salary.amount') }}" placeholder="e.g. 25000.00" required>
                        </div>
                        <div class="form-field">
                            <label>Salary Grade</label>
                            <input type="number" name="salary[salary_grade]" value="{{ old('salary.salary_grade') }}" placeholder="e.g. 10">
                        </div>
                        <div class="form-field">
                            <label>Step</label>
                            <input type="number" name="salary[step]" value="{{ old('salary.step') }}" placeholder="e.g. 1">
                        </div>
                    </div>
                </div>

                {{-- Step 4: Account --}}
                <div class="cemp-step" id="cemp-step-4" style="display:none">
                    <p class="form-section-label">ACCOUNT INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Password <span style="color:#dc2626">*</span></label>
                            <div class="create-pw-wrap">
                                <input id="cemp-password" type="password" name="password" placeholder="Create a password" required autocomplete="new-password">
                                <button type="button" class="create-eye" onclick="cempTogglePw('cemp-password','cemp-eye-1')">
                                    <svg id="cemp-eye-1" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-field">
                            <label>Confirm Password <span style="color:#dc2626">*</span></label>
                            <div class="create-pw-wrap">
                                <input id="cemp-password-confirm" type="password" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                                <button type="button" class="create-eye" onclick="cempTogglePw('cemp-password-confirm','cemp-eye-2')">
                                    <svg id="cemp-eye-2" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end pmodal-body --}}

            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" id="cemp-prev" onclick="cempNav(-1)" style="display:none">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back
                </button>
                <button type="button" class="modal-btn-ghost" onclick="closeCreateEmpModal()">Cancel</button>
                <button type="button" class="modal-btn-primary" id="cemp-next" onclick="cempNav(1)">
                    Next
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                <button type="submit" class="modal-btn-primary" id="cemp-submit" style="display:none">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Employee
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Import Employee Modal --}}
<div class="modal-overlay" id="employee-import-modal" style="display:none" onclick="closeEmployeeImportModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">IMPORT EMPLOYEES</span>
                <h3 class="modal-title">Upload CSV File</h3>
            </div>
            <button class="modal-close" onclick="closeEmployeeImportModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('employees.bulkStore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>CSV File <span style="color:#dc2626">*</span></label>
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;font-size:12px;color:#6b6a8a;line-height:1.7;margin-top:14px;">
                    <strong style="color:#0b044d;display:block;margin-bottom:4px;">CSV Format</strong>
                    first_name, middle_name, last_name, gender, email, password, date_of_birth, phone_number, employment_type, is_active, position_id, department_id, salary_type, amount, salary_grade, step, country, zip_code, city, address, province, work_schedule_id
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeEmployeeImportModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import CSV
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchView(viewId, btn) {
        if (btn.classList.contains('active')) return;
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + viewId).classList.add('active');
        btn.classList.add('active');
        document.getElementById('stats-employees').style.display = viewId === 'employees' ? 'grid' : 'none';
        document.getElementById('stats-departments').style.display = viewId === 'departments' ? 'grid' : 'none';
        document.getElementById('stats-positions').style.display = viewId === 'positions' ? 'grid' : 'none';
        // Update banner search placeholder
        const placeholders = { employees: 'Search employees...', departments: 'Search departments...', positions: 'Search positions...' };
        document.querySelector('.recruit-search').placeholder = placeholders[viewId];
        document.querySelector('.recruit-search').value = '';
    }

    function bannerSearch(q) {
        const active = document.querySelector('.tab-btn.active')?.textContent.trim().toLowerCase();
        if (active === 'employees')    filterEmployeeTable(q);
        else if (active === 'departments') filterDeptTable(q);
        else if (active === 'positions')   filterPosTable(q);
    }

    function filterEmployeeTable(q) {
        const search = (q !== undefined ? q : document.querySelector('.recruit-search').value).toLowerCase();
        const dept   = document.getElementById('dept-filter').value;
        const status = document.getElementById('status-filter').value;
        let visible  = 0;
        document.querySelectorAll('#employee-table tbody tr').forEach(row => {
            const name = row.querySelector('.emp-name')?.textContent.toLowerCase() || '';
            const id   = row.querySelector('.emp-id')?.textContent.toLowerCase() || '';
            const pos  = row.querySelector('.position-cell')?.textContent.toLowerCase() || '';
            const rowDept   = row.cells[2]?.textContent.trim() || '';
            const rowStatus = row.cells[5]?.textContent.trim() || '';
            const show = (!search || name.includes(search) || id.includes(search) || pos.includes(search))
                      && (!dept   || rowDept.includes(dept))
                      && (!status || rowStatus === status);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
    }

    function filterDeptTable(q) {
        const search = (q !== undefined ? q : document.querySelector('.recruit-search').value).toLowerCase();
        document.querySelectorAll('#dept-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = !search || text.includes(search) ? '' : 'none';
        });
    }

    function filterPosTable(q) {
        const search = (q !== undefined ? q : document.querySelector('.recruit-search').value).toLowerCase();
        document.querySelectorAll('#pos-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = !search || text.includes(search) ? '' : 'none';
        });
    }

    $(function () {
        if ($('#dept-table').length) {
            $('#dept-table').DataTable({
                columnDefs: [{ orderable: false, targets: [5] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No departments found' },
                dom: 'rtip',
            });
        }

        if ($('#employee-table').length) {
            $('#employee-table').DataTable({
                columnDefs: [{ orderable: false, targets: [5] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No employee records found' },
                dom: 'rtip',
            });
        }

        if ($('#pos-table').length) {
            $('#pos-table').DataTable({
                columnDefs: [{ orderable: false, targets: [2] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No positions found' },
                dom: 'rtip',
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

    function closeEmployeeImportModal() {
        document.getElementById('employee-import-modal').style.display = 'none';
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    $('#import-employee-btn').on('click', function (e) {
        e.preventDefault();
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.getElementById('employee-import-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeptCreateModal();
            closeDeptEditModal();
            closePosCreateModal();
            closePosEditModal();
            closeEmployeeImportModal();
            closeEditEmpModal();
            closeCreateEmpModal();
        }
    });
</script>

<script>
// ── Edit Employee Modal ──
let eempStep = 0;
const eempTotal = 4;

function openEditEmpModal(id, firstName, lastName, middleName, gender, dob, email, phone, address, city, province, country, zip, positionId, deptId, empType, scheduleId, isActive, salGrade, salStep, salType, salAmount) {
    eempStep = 0;
    eempRender();

    // Set form action
    var url = '{{ route("employees.update", ":id") }}'.replace(':id', id);
    document.getElementById('eemp-form').action = url;

    // Biographical
    document.getElementById('eemp-title').textContent = 'Edit: ' + firstName + ' ' + lastName;
    document.getElementById('eemp-first_name').value = firstName;
    document.getElementById('eemp-last_name').value  = lastName;
    document.getElementById('eemp-middle_name').value  = middleName;
    document.getElementById('eemp-dob').value         = dob;
    document.getElementById('eemp-email').value       = email;
    document.getElementById('eemp-phone').value       = phone;
    setSelect('eemp-gender', gender);

    // Address
    document.getElementById('eemp-address').value  = address;
    document.getElementById('eemp-city').value     = city;
    document.getElementById('eemp-province').value = province;
    document.getElementById('eemp-country').value  = country;
    document.getElementById('eemp-zip').value      = zip;

    // Employment
    setSelect('eemp-position',   positionId);
    setSelect('eemp-department', deptId);
    setSelect('eemp-emptype',    empType);
    setSelect('eemp-schedule',   scheduleId);
    setSelect('eemp-status',     isActive ? '1' : '0');

    // Salary
    setSelect('eemp-saltype', salType);
    document.getElementById('eemp-amount').value    = salAmount;
    document.getElementById('eemp-grade').value     = salGrade;
    document.getElementById('eemp-step-val').value  = salStep;

    document.getElementById('edit-emp-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeEditEmpModal() {
    document.getElementById('edit-emp-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function eempGoTo(step) {
    eempStep = step;
    eempRender();
}

function eempNav(dir) {
    eempStep = Math.max(0, Math.min(eempTotal - 1, eempStep + dir));
    eempRender();
}

function eempRender() {
    for (let i = 0; i < eempTotal; i++) {
        document.getElementById('eemp-step-' + i).style.display = i === eempStep ? 'block' : 'none';
    }
    document.querySelectorAll('#eemp-tabs .pmodal-tab').forEach((btn, i) => {
        btn.classList.toggle('active', i === eempStep);
    });
    const isFirst = eempStep === 0;
    const isLast  = eempStep === eempTotal - 1;
    document.getElementById('eemp-prev').style.display   = isFirst ? 'none' : 'inline-flex';
    document.getElementById('eemp-next').style.display   = isLast  ? 'none' : 'inline-flex';
    document.getElementById('eemp-submit').style.display = isLast  ? 'inline-flex' : 'none';
}

function setSelect(id, value) {
    const sel = document.getElementById(id);
    if (!sel) return;
    for (let opt of sel.options) {
        opt.selected = (opt.value == value);
    }
}
</script>

<script>
// ── Create Employee Modal ──
let cempStep = 0;
const cempTotal = 5;

function openCreateEmpModal() {
    cempStep = 0;
    cempRender();
    document.getElementById('create-emp-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCreateEmpModal() {
    document.getElementById('create-emp-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function cempGoTo(step) {
    cempStep = step;
    cempRender();
}

function cempNav(dir) {
    cempStep = Math.max(0, Math.min(cempTotal - 1, cempStep + dir));
    cempRender();
}

function cempRender() {
    for (let i = 0; i < cempTotal; i++) {
        document.getElementById('cemp-step-' + i).style.display = i === cempStep ? 'block' : 'none';
    }
    document.querySelectorAll('#cemp-tabs .pmodal-tab').forEach((btn, i) => {
        btn.classList.toggle('active', i === cempStep);
    });
    const isFirst = cempStep === 0;
    const isLast  = cempStep === cempTotal - 1;
    document.getElementById('cemp-prev').style.display   = isFirst ? 'none' : 'inline-flex';
    document.getElementById('cemp-next').style.display   = isLast  ? 'none' : 'inline-flex';
    document.getElementById('cemp-submit').style.display = isLast  ? 'inline-flex' : 'none';
}

function cempTogglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}

let cempEmailTimer;
document.getElementById('cemp-email').addEventListener('input', function () {
    clearTimeout(cempEmailTimer);
    const val = this.value;
    const err = document.getElementById('cemp-email-error');
    if (!val) { err.style.display = 'none'; return; }
    cempEmailTimer = setTimeout(() => {
        fetch('{{ route('employees.checkEmail') }}?email=' + encodeURIComponent(val))
            .then(r => r.json())
            .then(data => { err.style.display = data.exists ? 'block' : 'none'; });
    }, 400);
});

document.getElementById('cemp-form').addEventListener('submit', function (e) {
    if (document.getElementById('cemp-email-error').style.display === 'block') {
        e.preventDefault();
    }
});

const q = $('#banner-search').val();
if (q) {
    $('#tab-' + viewId + ' table').DataTable().search(q).draw();
}
</script>
@endpush
