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

@section('page-content')
<div style="margin-bottom:20px">
    <a href="{{ route('employees.index') }}" class="auth-nav-back">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Employees
    </a>
</div>

<div id="view-employees" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Archived Employees</p>
                <p class="table-sub">All archived and inactive personnel</p>
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
                            @if($employee->trashed())
                               <span class="badge-status on-hold" data-order="1">Archived</span>
                            @else
                                <span class="badge-status processed" data-order="0">Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <form action="{{ route('employees.restore', $employee->id) }}" method="post" style="display:inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-edit">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                    </button>
                                </form>
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

@endsection

@push('scripts')
<script>
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
                columnDefs: [{ orderable: false, targets: [3] }],
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
@endpush