@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h2>Salary Management</h2>
            <p>Manage employee salaries, grades, and steps</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $salaries->total() }} Records</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Salary List</p>
            <p class="table-sub">A list of all employee salaries and their details</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('salaries.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Salary
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Salary Type</th>
                    <th>Salary Grade</th>
                    <th>Step</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($salary->employee->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <span style="color:#fff;font-weight:700;font-size:12px">{{ substr($salary->employee->first_name, 0, 1) }}{{ substr($salary->employee->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#0b044d;margin:0">{{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</p>
                                <p style="font-size:11px;color:#9999bb;margin:0">{{ $salary->employee->position->title ?? 'No Position' }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="dept-tag" style="background:#eff6ff;color:#1e40af;border-color:#bfdbfe">{{ ucfirst($salary->salary_type->value) }}</span></td>
                    <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">SG {{ $salary->salary_grade ?? 'N/A' }}</span></td>
                    <td><span class="pay-cell">Step {{ $salary->step ?? 'N/A' }}</span></td>
                    <td><span class="pay-cell" style="font-weight:700">₱{{ number_format($salary->amount, 2) }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('salaries.edit', $salary) }}" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('salaries.destroy', $salary) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this salary record?')">
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
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No salary records found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top:20px">
        {{ $salaries->links() }}
    </div>
</div>

@endsection
