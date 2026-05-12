@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
    use App\Enums\LeaveStatus;

    $monthlyPayroll = 0;
    $employeesOnLeave = 0;
    $employeesPendingLeave = 0;
    $totalEmployees = isset($employees) ? $employees->count() : 0;
    $presentToday = 0;


    if (isset($employees)) {
        foreach ($employees as $employee) {
            foreach($employee->attendance as $attendance) {
                $presentToday = $attendance->presentToday()->count();
            }
            $monthlyPayroll += method_exists($employee, 'netPay') ? $employee->netPay() : 0;
            $employeesOnLeave += $employee->leaves()->where('leave_status', LeaveStatus::Approved->value)->count();
            $employeesPendingLeave += $employee->leaves()->where('leave_status', LeaveStatus::Pending->value)->count();
        }
    }
@endphp

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
            <h2>Welcome back, {{ auth()->user()->name }}!</h2>
            <p>{{ config('app.carbon_date') }} &nbsp;·&nbsp; PRIME HRIS Dashboard</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            System Online
        </span>
        <span class="banner-badge outline">FY {{ config('app.carbon_year') }}</span>
    </div>
</div>

<div class="quick-actions-row">
    <a href="{{ route('employees.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#f0effe"><svg width="16" height="16" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        Manage Employees
    </a>
    <a href="{{ route('employee_compensations.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#e8f9ef">
            <svg width="22" height="22" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        Manage Salaries
    </a>
    <a href="{{ route('attendances.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#e8f9ef"><svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        Track Attendance
    </a>
    <a href="{{ route('leave_requests.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#fefce8"><svg width="16" height="16" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        Manage Leaves
    </a>
    <a href="{{ route('payroll.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#fdf0ef"><svg width="16" height="16" viewBox="0 0 24 24" fill="#8e1e18" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg></div>
        Process Payroll
    </a>
</div>

<div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:1.5px solid #eceaf8;padding-bottom:0" hidden>
    <button class="tab-btn active" onclick="switchView('overview', this)">Overview</button>
    <button class="tab-btn" onclick="switchView('directory', this)">Employee Directory</button>
    <button class="tab-btn" onclick="switchView('leaves', this)">Leave Requests</button>
</div>

<div id="view-overview" class="tab-pane active">
    <div class="stats-grid stats-grid-4">
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Total Employees</p>
                <div class="stat-icon-wrap" style="background:#f0effe">
                    <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $totalEmployees }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#22c55e"></span>
                <p class="stat-sub">Active employees</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Present Today</p>
                <div class="stat-icon-wrap" style="background:#e8f9ef">
                    <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $presentToday }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#22c55e"></span>
                <p class="stat-sub">Number of employees</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">On Leave</p>
                <div class="stat-icon-wrap" style="background:#fefce8">
                    <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $employeesOnLeave }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#f59e0b"></span>
                <p class="stat-sub">{{ $employeesPendingLeave }} pending approval</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Monthly Payroll</p>
                <div class="stat-icon-wrap" style="background:#fdf0ef">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="#8e1e18" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                </div>
            </div>
            <p class="stat-value" style="font-size:20px">₱{{ number_format($monthlyPayroll, 2) }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">For {{ config('app.carbon_month', date('F')) }}</p>
            </div>
        </div>
    </div>

    <div class="charts-row">

        <!-- Department Chart -->
        <div class="chart-card">
            <p class="chart-title">Department Distribution</p>
            <div class="chart-body">
                <canvas id="departmentPieChart"></canvas>
            </div>
        </div>

        <!-- Budget Chart -->
        <div class="chart-card">
            <p class="chart-title">Payroll Budget Overview</p>
            <div class="chart-body">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>

        <!-- Attendance Chart -->
        <div class="chart-card">
            <p class="chart-title">Monthly Attendance Per Department</p>
            <div class="chart-body">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

    </div>

    {{-- Bottom Row: Pending Leaves + Side Col --}}
    <div class="bottom-row">

        {{-- Pending Leave Requests --}}
        <div class="table-section mb-0">
            <div class="table-header">
                <div>
                    <p class="table-title">Pending Leave Requests</p>
                    <p class="table-sub">Requires your approval</p>
                </div>
                <a href="{{ route('leave_requests.index') }}" class="btn-export">View All</a>
            </div>
            <div class="table-wrapper">
                <table class="payroll-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($leaveRequests) && count($leaveRequests) > 0)
                        @foreach($leaveRequests->take(5) as $leave)
                        <tr>
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($leave->employee->id % 5)] }};width:30px;height:30px;font-size:10px">
                                        {{ strtoupper(substr($leave->employee->first_name,0,1).substr($leave->employee->last_name,0,1)) }}
                                    </div>
                                    <p class="emp-name" style="margin:0">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                </div>
                            </td>
                            <td><span class="dept-tag">{{ $leave->leaveType->leave_type ?? 'N/A' }}</span></td>
                            <td style="font-size:12.5px;color:#5a5888">{{ $leave->leave_duration }} days</td>
                            <td>
                                @if($leave->leave_status === 'Approved')
                                    <span class="badge-status processed">Approved</span>
                                @elseif($leave->leave_status === 'Pending')
                                    <span class="badge-status pending">Pending</span>
                                @else
                                    <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No pending leave requests</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Side Column --}}
        <div class="side-col">

            {{-- Department Breakdown --}}
            <div class="table-section mb-0">
                <div class="table-header" style="padding:16px 20px">
                    <p class="table-title" style="font-size:13px">Department Breakdown</p>
                </div>
                @php
                    $deptCounts = isset($employees) ? $employees->groupBy(fn($e) => $e->department->name ?? 'N/A') : collect();
                    $deptTotal  = $deptCounts->sum(fn($g) => $g->count());
                    $deptColors = ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'];
                @endphp
                <div style="padding:4px 20px 16px">
                    @forelse($deptCounts->take(5) as $deptName => $deptGroup)
                    @php $deptCount = $deptGroup->count(); $deptPct = $deptTotal > 0 ? round($deptCount/$deptTotal*100) : 0; $deptColor = $deptColors[$loop->index % 5]; @endphp
                    <div style="margin-bottom:10px">
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                            <span style="font-weight:600;color:#0b044d">{{ $deptName }}</span>
                            <span style="color:#9999bb">{{ $deptCount }}</span>
                        </div>
                        <div style="height:6px;background:#f0effe;border-radius:99px;overflow:hidden">
                            <div style="height:100%;width:{{ $deptPct }}%;background:{{ $deptColor }};border-radius:99px"></div>
                        </div>
                    </div>
                    @empty
                    <p style="font-size:12px;color:#9999bb;text-align:center;padding:12px 0">No department data</p>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Events --}}
            <div class="stat-card no-margin">
                <p class="stat-label" style="margin-bottom:12px">Upcoming Events</p>
                @php
                $events = [
                    ['label'=>'Payroll Release','date'=>'15','color'=>'#0b044d'],
                    ['label'=>'CSC Training','date'=>'18','color'=>'#8e1e18'],
                    ['label'=>'Performance Review','date'=>'25','color'=>'#15803d'],
                ];
                @endphp
                @foreach($events as $ev)
                <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f7f6ff">
                    <div style="width:36px;height:36px;background:{{ $ev['color'] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div style="flex:1">
                        <p style="font-size:12.5px;font-weight:600;color:#0b044d;margin:0 0 2px">{{ $ev['label'] }}</p>
                        <p style="font-size:11px;color:#9999bb;margin:0">{{ config('app.carbon_month', date('M')) }} {{ $ev['date'] }}, {{ date('Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<!-- ================== TAB: EMPLOYEE DIRECTORY ================== -->
<div id="view-directory" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Employee Directory</p>
                <p class="table-sub">All active government personnel</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="dir-search" placeholder="Search employees..." class="search-input" oninput="filterDirectory()">
                </div>
                <select class="filter-select" id="dir-type" onchange="filterDirectory()">
                    <option value="">All Types</option>
                    <option value="Permanent">Permanent</option>
                    <option value="Job Order">Job Order</option>
                </select>
                <a href="{{ route('employees.index') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Manage Employees
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="attendance-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Employment Type</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="dir-tbody">
                @if(isset($employees) && count($employees) > 0)
                    @foreach ($employees as $employee)
                        <tr data-name="{{ strtolower($employee->first_name.' '.$employee->last_name) }}" data-type="{{ $employee->employment_type }}">
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
                            <td><span class="position-cell">{{ $employee->position->title ?? 'N/A' }}</span></td>
                            <td><span class="dept-tag">{{ $employee->department->name ?? 'N/A' }}</span></td>
                            <td>
                                <span class="dept-tag" style="background:{{ $employee->employment_type === 'Permanent' ? '#e8f9ef' : '#fefce8' }};color:{{ $employee->employment_type === 'Permanent' ? '#15803d' : '#a16207' }};border-color:{{ $employee->employment_type === 'Permanent' ? '#bbf7d0' : '#fde68a' }}">
                                    {{ $employee->employment_type }}
                                </span>
                            </td>
                            <td style="font-size:12.5px;color:#6b6a8a">{{ \Carbon\Carbon::parse($employee->created_at)->format('M d, Y') }}</td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge-status processed">Active</span>
                                @else
                                    <span class="badge-status on-hold">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn-view">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================== TAB: LEAVES ================== -->
<div id="view-leaves" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Recent Leave Requests</p>
                <p class="table-sub">Requires your approval</p>
            </div>
            <a href="{{ route('leave_requests.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Manage Leaves
            </a>
        </div>
        <div class="table-wrapper">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($leaveRequests) && count($leaveRequests) > 0)
                    @foreach($leaveRequests as $leave)
                        <tr>
                            <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($leave->employee->id % 5)] }}">
                                        {{ strtoupper(substr($leave->employee->first_name, 0, 1) . substr($leave->employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="emp-name">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                        <p class="emp-id">EMP-{{ str_pad($leave->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $leave->leaveType->leave_type ?? 'N/A' }}</td>
                            <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $leave->leave_duration }} days</span></td>
                            <td>
                                @if($leave->leave_status === 'Approved')
                                    <span class="badge-status processed">Approved</span>
                                @elseif($leave->leave_status === 'Pending')
                                    <span class="badge-status pending">Pending</span>
                                @else
                                    <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="empty-state" style="text-align: center; padding: 40px;">
                            <div style="display: flex; justify-content: center; margin-bottom: 12px;">
                                <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <p style="font-size:14px;color:#9999bb;">No leave records found</p>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const departments = @json($departments);

        const labels = departments.map(dept => dept.name);
        const data = departments.map(dept => dept.employees_count);

        const ctx = document.getElementById('departmentPieChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Personnel per Department',
                    data: data,
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#14b8a6',
                        '#eab308',
                        '#ec4899'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        align: 'center'
                    },
                    title: {
                        display: true,
                        text: 'Personnel Distribution per Department'
                    }
                }
            }
        });
    </script>

    <script>
        const budgetChart = new Chart(
            document.getElementById('budgetChart'),
            {
                type: 'bar',
                data: {
                    labels: ['Gross Pay', 'Deductions', 'Net Pay'],
                    datasets: [{
                        label: 'Amount (₱)',
                        data: [
                            {{ $totalGross }},
                            {{ $totalDeductions }},
                            {{ $totalNet }}
                        ],
                        backgroundColor: [
                            '#0b044d',
                            '#f59e0b',
                            '#22c55e'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
    </script>

    <script>
        const attendanceChart = new Chart(
            document.getElementById('attendanceChart'),
            {
                type: 'bar',
                data: {
                    labels: @json($departments->pluck('name')),
                    datasets: [{
                        label: 'Total Attendance',
                        data: @json($attendanceData),
                        backgroundColor: '#15803d'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            }
        );
    </script>

    <script>
        function switchView(viewId, btn) {
            if (btn.classList.contains('active')) return;
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');
            btn.classList.add('active');
        }

        function filterDirectory() {
            const search = document.getElementById('dir-search').value.toLowerCase();
            const type   = document.getElementById('dir-type').value;
            document.querySelectorAll('#dir-tbody tr').forEach(row => {
                const matchName = !search || row.dataset.name.includes(search);
                const matchType = !type  || row.dataset.type === type;
                row.style.display = matchName && matchType ? '' : 'none';
            });
        }
    </script>
@endpush
