@extends('layouts.admin')

@php
    $totalArchived = $employees->count();
@endphp

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><polyline points="3 7 12 2 21 7"/></svg>
        </div>
        <div>
            <h2>Archived Employees</h2>
            <p>{{ config('app.carbon_date') }} &nbsp;·&nbsp; {{ $totalArchived }} archived {{ $totalArchived === 1 ? 'record' : 'records' }}</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search archived employees..." class="recruit-search" oninput="filterArchive(this.value)">
        </div>
    </div>
</div>

<div id="view-employees" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Archived Employees</p>
                <p class="table-sub">All archived and inactive personnel</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
                <select class="filter-select" id="dept-filter">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="position-filter">
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
function filterArchive(q) {
    const search = (q || '').toLowerCase();
    const dept   = document.getElementById('dept-filter').value;
    const pos    = document.getElementById('position-filter').value;
    document.querySelectorAll('#attendance-table tbody tr').forEach(row => {
        const name    = row.querySelector('.emp-name')?.textContent.toLowerCase() || '';
        const id      = row.querySelector('.emp-id')?.textContent.toLowerCase() || '';
        const rowDept = row.cells[2]?.textContent.trim() || '';
        const rowPos  = row.cells[1]?.textContent.trim() || '';
        const show = (!search || name.includes(search) || id.includes(search))
                  && (!dept || rowDept.includes(dept))
                  && (!pos  || rowPos.includes(pos));
        row.style.display = show ? '' : 'none';
    });
}
document.getElementById('dept-filter').addEventListener('change', () => filterArchive(document.querySelector('.recruit-search').value));
document.getElementById('position-filter').addEventListener('change', () => filterArchive(document.querySelector('.recruit-search').value));
</script>
@endpush