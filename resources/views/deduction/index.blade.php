@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#d9bb00" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
        </div>
        <div>
            <h2>Deductions</h2>
            <p>Manage mandatory and optional deductions</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $deductions->count() }} Deductions</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Deductions</p>
            <p class="table-sub">Manage mandatory and optional deductions</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('deductions.create') }}" class="modal-btn-primary">
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
                    <th>Deduction Name</th>
                    <th>Rate</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($deductions as $deduction)
                <tr>
                    <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($deduction->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:#0b044d">{{ $deduction->name }}</span>
                        </div>
                    </td>
                    <td>
                        @if($deduction->rate == 0)
                            <span style="font-size:12.5px;color:#9999bb">—</span>
                        @else
                            <span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $deduction->rate * 100 }}%</span>
                        @endif
                    </td>
                    <td>
                        <span class="dept-tag" style="{{ $deduction->type === 'Mandatory' ? 'background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce' : 'background:#f0effe;color:#0b044d' }}">
                            {{ $deduction->type }}
                        </span>
                    </td>
                    <td><span style="font-size:12.5px;color:#5a5888">{{ $deduction->description ?? '—' }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('deductions.edit', $deduction) }}" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            @if ($deduction->type=='Optional')
                            <form action="{{ route('deductions.destroy', $deduction) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this deduction?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                            @else
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#d9d9ee" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No deductions found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
