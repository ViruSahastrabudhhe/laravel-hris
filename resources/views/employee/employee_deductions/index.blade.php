@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#d9bb00" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
        </div>
        <div>
            <h2>Deduction Management</h2>
            <p>Manage deductions applied to employees</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $employees->count() }} Employees</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Deduction Management</p>
            <p class="table-sub">Manage deductions applied to employees</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('employee_deductions.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Deduction
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Mandatory Deductions</th>
                    <th>Optional Deductions</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
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
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px">
                        @forelse($employee->employeeDeduction as $deduction)
                            @if($deduction->deduction->type === 'Mandatory')
                                <span style="font-size:12.5px;color:#5a5888">{{ $deduction->deduction->name }}: <strong style="color:#8e1e18">₱{{ number_format($deduction->amount, 2) }}</strong></span>
                            @endif
                        @empty
                            <span style="font-size:12px;color:#9999bb">None</span>
                        @endforelse
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px">
                        @forelse($employee->employeeDeduction as $deduction)
                            @if($deduction->deduction->type === 'Optional')
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span style="font-size:12.5px;color:#5a5888">{{ $deduction->deduction->name }}: <strong style="color:#8e1e18">₱{{ number_format($deduction->amount, 2) }}</strong></span>
                                    <form id="delete-form-{{ $deduction->id }}" action="{{ route('employee_deductions.destroy', $deduction) }}" method="post" style="display:inline" onsubmit="return confirm('Remove this deduction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce;padding:2px 8px;font-size:11px">Remove</button>
                                    </form>
                                </div>
                            @endif
                        @empty
                            <span style="font-size:12px;color:#9999bb">None</span>
                        @endforelse
                        </div>
                    </td>
                    <td><span class="deduction" style="font-size:13px;font-weight:700">₱{{ number_format($employee->employeeDeduction->sum('amount'), 2) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#d9d9ee" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No employee deductions found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
