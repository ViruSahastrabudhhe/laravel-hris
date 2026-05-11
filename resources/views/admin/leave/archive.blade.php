@extends('layouts.admin')
@php $hideChat = true; @endphp

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </div>
        <div>
            <h2>Leave Archive</h2>
            <p>{{ config('app.carbon_date') }} &nbsp;·&nbsp; Archived Leave Requests</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="15" height="15" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="archive-search" placeholder="Search archived..." class="recruit-search">
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Archived Leave Requests</p>
            <p class="table-sub">Municipality of Pagsanjan &nbsp;·&nbsp; {{ $total }} archived records</p>
        </div>
        <div class="table-actions"></div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="archive-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Archived On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($archivedLeaves as $leave)
                <tr>
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
                    <td><span class="dept-tag">{{ $leave->employee->department->name }}</span></td>
                    <td style="font-size:13px;color:#0b044d;font-weight:500">{{ $leave->leaveType->leave_type }}</td>
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</span></td>
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span></td>
                    <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $leave->leave_duration }} {{ $leave->leave_duration == 1 ? 'day' : 'days' }}</span></td>
                    <td>
                        @if($leave->leave_status === 'Approved')
                            <span class="badge-status processed">Approved</span>
                        @elseif($leave->leave_status === 'Declined')
                            <span class="badge-status on-hold">Declined</span>
                        @else
                            <span class="badge-status pending">{{ $leave->leave_status }}</span>
                        @endif
                    </td>
                    <td><span style="font-size:12.5px;color:#9999bb">{{ \Carbon\Carbon::parse($leave->deleted_at)->format('M d, Y') }}</span></td>
                    <td>
                        <div class="row-actions">
                            <form action="{{ route('leave_requests.restore', $leave->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Restore this leave request?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-restore">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.5"/></svg>
                                    Restore
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:60px 20px">
                        <div style="width:80px;height:80px;margin:0 auto 20px;background:linear-gradient(135deg,#f7f6ff,#eceaf8);border-radius:50%;display:flex;align-items:center;justify-content:center">
                            <svg width="32" height="32" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                        </div>
                        <p style="font-size:14px;font-weight:700;color:#0b044d;margin:0 0 6px">No Archived Records</p>
                        <p style="font-size:13px;color:#9999bb;margin:0">Archived leave requests will appear here</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <p>Showing <strong>{{ $total }}</strong> archived records</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    const archiveTable = $('#archive-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 8] }],
        pageLength: 25,
        language: { emptyTable: 'No archived leave requests found' },
        dom: 'rtip',
    });

    $('#archive-search').on('keyup', function () {
        archiveTable.search(this.value).draw();
    });
});
</script>
@endpush
