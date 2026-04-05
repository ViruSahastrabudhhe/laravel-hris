@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <h2>Leave Balances</h2>
            <p>Manage employee leave balance allocations</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $leaveBalances->count() }} Employees</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Leave Balances</p>
            <p class="table-sub">Manage employee leave balance allocations</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('leave_balances.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Leave Balance
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($leaveBalances as $leaveBalance)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($leaveBalance->employee->id % 5)] }}">
                                {{ strtoupper(substr($leaveBalance->employee->first_name, 0, 1) . substr($leaveBalance->employee->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="emp-name">{{ $leaveBalance->employee->first_name }} {{ $leaveBalance->employee->last_name }}</p>
                                <p class="emp-id">EMP-{{ str_pad($leaveBalance->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="dept-tag" style="{{ $leaveBalance->leave_balance > 5 ? 'background:#e8f9ef;color:#15803d;border-color:#bbf7d0' : 'background:#fefce8;color:#a16207;border-color:#fde68a' }}">
                            {{ $leaveBalance->leave_balance }} days
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('leave_balances.destroy', $leaveBalance) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this leave balance?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No leave balances found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
