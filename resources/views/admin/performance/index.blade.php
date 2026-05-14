@extends('layouts.admin')
@php $hideChat = true; @endphp

@section('page-content')

    @php
        $totalForms      = $ipcrForms->count();
        $pendingForms    = $ipcrForms->where('status', 'Draft')->count();
        $approvedForms   = $ipcrForms->where('status', 'Approved')->count();
        $avgRating       = $ipcrForms->whereNotNull('final_rating')->avg('final_rating');
    @endphp

    {{-- Banner --}}
    <div class="welcome-banner">
        <div class="banner-left">
            <div class="banner-icon">
                <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <div>
                <h2>IPCR Performance Dashboard</h2>
                <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Individual Performance Commitment Review</p>
            </div>
        </div>
        <div class="banner-right">
            <div>
                <select class="filter-select" id="cycle-filter">
                    <option value="">All Cycles</option>
                    @foreach($cycles->where('is_active', true) as $cycle)
                        <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="recruit-search-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="banner-search" placeholder="Search evaluations..." class="recruit-search" oninput="filterPerformanceTable(this.value)">
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div id="stats-performances" class="stats-grid stats-grid-4">
        <div class="stat-card" style="--accent-color: #0b044d">
            <div class="stat-top">
                <p class="stat-label">Total Forms</p>
                <div class="stat-icon-wrap" style="background: rgba(11,4,77,0.1)">
                    <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <h2 class="stat-value">{{ $totalForms }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">All IPCR forms</p>
            </div>
        </div>

        <div class="stat-card" style="--accent-color: #d9bb00">
            <div class="stat-top">
                <p class="stat-label">Pending Review</p>
                <div class="stat-icon-wrap" style="background: rgba(217,187,0,0.1)">
                    <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <h2 class="stat-value">{{ $pendingForms }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#d9bb00"></span>
                <p class="stat-sub">Awaiting approval</p>
            </div>
        </div>

        <div class="stat-card" style="--accent-color: #15803d">
            <div class="stat-top">
                <p class="stat-label">Approved</p>
                <div class="stat-icon-wrap" style="background: rgba(21,128,61,0.1)">
                    <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
            <h2 class="stat-value">{{ $approvedForms }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#15803d"></span>
                <p class="stat-sub">Fully approved</p>
            </div>
        </div>

        <div class="stat-card" style="--accent-color: #1d4ed8">
            <div class="stat-top">
                <p class="stat-label">Avg. Rating</p>
                <div class="stat-icon-wrap" style="background: rgba(29,78,216,0.1)">
                    <svg width="18" height="18" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
            </div>
            <h2 class="stat-value">{{ $avgRating ? number_format($avgRating, 2) : '—' }}</h2>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#1d4ed8"></span>
                <p class="stat-sub">Final rating average</p>
            </div>
        </div>
    </div>

    <div class="tab-buttons">
        <button class="tab-btn active" onclick="switchView('performances', this)">IPCR Forms</button>
        <button class="tab-btn" onclick="switchView('cycles', this)">Performance Cycles</button>
    </div>

    {{-- Table Section --}}
    <div id="tab-performances" class="tab-pane active">
        <div class="table-section" style="margin-bottom:22px">
            <div class="table-header">
                <div>
                    <p class="table-title">IPCR Forms</p>
                    <p class="table-sub">{{ config('app.name') }} · <span id="showing-count">{{ $totalForms }}</span> of {{ $totalForms }} forms</p>
                </div>
                <div class="table-actions">
                    <select class="filter-select" id="status-filter">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Approved">Approved</option>
                    </select>
                    <button class="btn-export" hidden>
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export
                    </button>
                    <button onclick="openCreateIPCRForm()" class="modal-btn-ghost" style="gap:6px;display:inline-flex;align-items:center">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Form
                    </button>
                    <button onclick="openBulkCreateIPCRForm()" class="modal-btn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Bulk Create
                    </button>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="payroll-table" id="performance-table">
                    <thead>
                    <tr>
                        <th>Form ID</th>
                        <th>Employee</th>
                        <th>Performance Cycle</th>
                        <th>Status</th>
                        <th>Final Rating</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="performance-table-body">
                    @forelse($ipcrForms as $form)
                        @php
                            $statusClass = match($form->status) {
                                'Approved' => 'processed',
                                'Draft'  => 'pending',
                                default    => 'pending',
                            };
                            $rating = $form->final_rating;
                            $ratingColor = match(true) {
                                $rating === null          => '#9999bb',
                                $rating >= 4.5            => '#15803d',
                                $rating >= 3.5            => '#1d4ed8',
                                $rating >= 2.5            => '#d9bb00',
                                default                   => '#8e1e18',
                            };
                        @endphp
                        <tr data-status="{{ strtolower($form->status) }}">
                            <td style="font-size:12.5px;color:#6b6a8a;font-weight:500">IPCR-{{ str_pad($form->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td><span class="position-cell">{{ $form->employee->first_name }} {{ $form->employee->last_name }}</span></td>
                            <td><span class="dept-tag">{{ $form->cycle->name }}</span></td>
                            <td><span class="badge-status {{ $statusClass }}">{{ $form->status }}</span></td>
                            <td>
                                @if($rating !== null)
                                    <span style="font-size:13px;font-weight:700;color:{{ $ratingColor }}">{{ number_format($rating, 2) }}</span>
                                    <span style="font-size:11px;color:#9999bb"> / 5.00</span>
                                @else
                                    <span style="font-size:12px;color:#9999bb">Not yet rated</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    @if (!$form->isApproved())
                                        <button class="btn-view" onclick="openEditIPCRForm(this)"
                                                data-employee-id="{{ $form->employee->id }}"
                                                data-employee-name="{{ $form->employee->first_name }} {{ $form->employee->last_name }}">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>

                                        @if ($form->entries->isNotEmpty() && $form->entries->every(function ($entry) {
                                            return !is_null($entry->actual_accomplishments)
                                                && !is_null($entry->quality_rating)
                                                && !is_null($entry->efficiency_rating)
                                                && !is_null($entry->timeliness_rating);
                                        }))
                                            <form action="{{ route('performance_management.computeFinalRating', $form->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-edit" title="Compute Rating">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                                </button>
                                            </form>

                                            <button class="btn-success" onclick="openApproveIPCRForm(this)"
                                                    data-form_id="{{ $form->id }}"
                                                    data-employee_name="{{ $form->employee->first_name }} {{ $form->employee->last_name }}"
                                                    title="Approve">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            </button>
                                        @endif

                                        <form action="{{ route('performance_management.destroy', $form->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this IPCR form?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-view" onclick="openViewIPCRForm(this)"
                                                data-employee-id="{{ $form->employee->id }}"
                                                data-employee_name="{{ $form->employee->first_name }} {{ $form->employee->last_name }}"
                                        >
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>

                                        <form action="{{ route('performance_management.destroy', $form->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this IPCR form?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
                <div class="empty-state" id="empty-state" style="display:none;text-align:center;padding:40px 20px">
                    <p style="font-size:13px;color:#9999bb;margin:0">No IPCR forms match your criteria</p>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-cycles" class="tab-pane">
        <div class="table-section" style="margin-bottom:22px">
            <div class="table-header">
                <div>
                    <p class="table-title">Performance Cycles</p>
                    <p class="table-sub">{{ config('app.name') }} · <span id="showing-count">{{ $totalForms }}</span> of {{ $totalForms }} forms</p>
                </div>
                <div class="table-actions">
                    <select class="filter-select" id="status-filter">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Approved">Approved</option>
                    </select>
                    <button class="btn-export" hidden>
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export
                    </button>
                    <button onclick="openCreatePerformanceCycle()" class="modal-btn-primary" style="gap:6px;display:inline-flex;align-items:center">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        New Cycle
                    </button>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="payroll-table" id="cycles-table">
                    <thead>
                        <tr>
                            <th>Cycle ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cycles as $cycle)
                        @php
                            $statusClass = $cycle->is_active ? 'processed' : 'pending';
                        @endphp

                        <tr>
                            <td style="font-size:12.5px;color:#6b6a8a;font-weight:500">
                                CYCLE-{{ str_pad($cycle->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td><span class="position-cell">{{ $cycle->name }}</span></td>
                            <td><span class="dept-tag">{{ \Carbon\Carbon::parse($cycle->start_date)->format('M d, Y') }}</span></td>
                            <td><span class="dept-tag">{{ \Carbon\Carbon::parse($cycle->end_date)->format('M d, Y') }}</span></td>
                            <td>
                                @if($cycle->is_active)
                                    <span class="badge-status processed">Active</span>
                                @else
                                    <span class="badge-status pending">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    @if (!$cycle->is_active)
                                    <form action="{{ route('performance_management.activateCycle', $cycle->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-success" title="Toggle Active">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('performance_management.deactivateCycle', $cycle->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-danger" title="Toggle Active">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('performance_management.destroyCycle', $cycle->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this cycle?')"
                                          style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Performance Cycle Modal --}}
    <div class="modal-overlay" id="createPerformanceCycleModal" style="display:none">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">PERFORMANCE</span>
                    <h3 class="modal-title">Create Performance Cycle</h3>
                    <p class="modal-sub">Define a new evaluation period</p>
                </div>
                <button class="modal-close" onclick="closeModal('createPerformanceCycleModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('performance_management.storePerformanceCycle') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    <div class="form-field">
                        <label>Cycle Name <span style="color:#dc2626">*</span></label>
                        <input type="text" name="name" placeholder="e.g. Q1 2025 Performance Cycle" required>
                    </div>
                    <div class="form-field">
                        <label>Start Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="form-field">
                        <label>End Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" required>
                    </div>
                    <div class="form-field">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="checkbox" name="is_active" value="1" checked style="width:auto">
                            <span>Set as Active Cycle</span>
                        </label>
                    </div>
                    <div style="background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12px;color:#92400e;margin-top:4px">
                        ⚠️ Setting this as active will overwrite the current active cycle.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('createPerformanceCycleModal')">Cancel</button>
                    <button type="submit" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Create Cycle
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create IPCR Form Modal --}}
    <div class="modal-overlay" id="createIPCRFormModal" style="display:none">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORMS</span>
                    <h3 class="modal-title">Add IPCR Form</h3>
                    <p class="modal-sub">Create a form for a specific employee</p>
                </div>
                <button class="modal-close" onclick="closeModal('createIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('performance_management.storeIPCRForm') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    @if($cycles->isEmpty() || $cycles->where('is_active', true)->isEmpty())
                        <div style="text-align:center;padding:32px 0">
                            <p style="font-size:13px;color:#6b6a8a;margin:0">No performance cycles found. Create one first.</p>
                        </div>
                    @else
                        <div class="form-field">
                            <label>Employee <span style="color:#dc2626">*</span></label>
                            <select name="employee_id" required>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Performance Cycle <span style="color:#dc2626">*</span></label>
                            <select name="performance_cycle_id" required>
                                @foreach($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('createIPCRFormModal')">Cancel</button>
                    @if($cycles->where('is_active', true)->isNotEmpty())
                        <button type="submit" class="modal-btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Create Form
                        </button>
                    @else
                        <button type="button" class="modal-btn-primary" onclick="closeModal('createIPCRFormModal'); openCreatePerformanceCycle()">
                            Create Cycle First
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Create IPCR Form Modal --}}
    <div class="modal-overlay" id="bulkCreateIPCRFormModal" style="display:none">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORMS</span>
                    <h3 class="modal-title">Bulk Create IPCR Forms</h3>
                    <p class="modal-sub">Generate forms for all employees</p>
                </div>
                <button class="modal-close" onclick="closeModal('bulkCreateIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('performance_management.bulkStoreIPCRForm') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    @if($cycles->isEmpty() || $cycles->where('is_active', true)->isEmpty())
                        <div style="text-align:center;padding:32px 0">
                            <p style="font-size:13px;color:#6b6a8a;margin:0">No performance cycles found. Create one first.</p>
                        </div>
                    @else
                        <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#0b044d,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:700;color:#0b044d;margin:0">All Employees</p>
                                <p style="font-size:11px;color:#9999bb;margin:2px 0 0">IPCR forms will be created for every employee in the system.</p>
                            </div>
                        </div>
                        <div class="form-field">
                            <label>Performance Cycle <span style="color:#dc2626">*</span></label>
                            <select name="performance_cycle_id" required>
                                @foreach($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('bulkCreateIPCRFormModal')">Cancel</button>
                    @if($cycles->where('is_active', true)->isNotEmpty())
                        <button type="submit" class="modal-btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Bulk Create
                        </button>
                    @else
                        <button type="button" class="modal-btn-primary" onclick="closeModal('bulkCreateIPCRFormModal'); openCreatePerformanceCycle()">
                            Create Cycle First
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Edit IPCR Form Modal --}}
    <div class="modal-overlay" id="editIPCRFormModal" style="display:none">
        <div class="modal-box" style="max-width:1100px" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORM</span>
                    <h3 class="modal-title" id="ipcr-modal-title">View & Update Entries</h3>
                    <p class="modal-sub" id="ipcr-modal-sub">For Employee Name</p>
                </div>
                <button class="modal-close" onclick="closeModal('editIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="ipcr-update-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height:65vh;overflow-y:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                        <tr style="background:#f7f6ff;text-align:left;">
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">KRA</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Objectives</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Success Indicators</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Actual Accomplishments</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Quality</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Efficiency</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Timeliness</th>
                        </tr>
                        </thead>
                        <tbody id="ipcr-table-body"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('editIPCRFormModal')">Cancel</button>
                    <button type="submit" id="editIPCRSubmitBtn" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- View IPCR Form Modal --}}
    <div class="modal-overlay" id="viewIPCRFormModal" style="display:none">
        <div class="modal-box" style="max-width:1100px" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORM</span>
                    <h3 class="modal-title" id="view-ipcr-title">View Entries</h3>
                    <p class="modal-sub" id="view-ipcr-sub">For Employee Name</p>
                </div>
                <button class="modal-close" onclick="closeModal('viewIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="ipcr-update-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height:65vh;overflow-y:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                        <tr style="background:#f7f6ff;text-align:left;">
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">KRA</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Objectives</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Success Indicators</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Actual Accomplishments</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Quality</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Efficiency</th>
                            <th style="padding:10px 12px;color:#6b6a8a;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase">Timeliness</th>
                        </tr>
                        </thead>
                        <tbody id="view-ipcr-table-body"></tbody>
                    </table>

                    <div style="display:flex; gap:12px; margin-top: 1rem;">
                        <div class="form-field" style="flex:1; padding: 12px; background: #f7f6ff; border-radius: 10px;">
                            <label>Development Needs </label>
                            <p id="viewIPCRDevelopmentNeeds">Insert Development Needs Here</p>
                        </div>
                        <div class="form-field" style="flex:1; padding: 12px; background: #f7f6ff; border-radius: 10px;">
                            <label>Recommended Training </label>
                            <p id="viewIPCRRecommendedTraining">Insert Recommended Training Here</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('viewIPCRFormModal')">Close</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Approve IPCR Modal --}}
    <div class="modal-overlay" id="approveIPCRFormModal" style="display:none">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORMS</span>
                    <h3 class="modal-title">Approve IPCR Form</h3>
                    <p class="modal-sub" id="approveIPCRTitle">For Employee</p>
                </div>
                <button class="modal-close" onclick="closeModal('approveIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="ipcr-approve-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    <input type="hidden" name="ipcr_form_id" id="editIPCRFormID">
                    <div class="form-field">
                        <label>Development Needs <span style="color:#dc2626">*</span></label>
                        <textarea name="development_needs" rows="3" placeholder="Describe areas for development..." required></textarea>
                    </div>
                    <div class="form-field">
                        <label>Recommended Training <span style="color:#dc2626">*</span></label>
                        <input type="text" name="recommended_training" placeholder="e.g. Leadership Seminar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('approveIPCRFormModal')">Cancel</button>
                    <button type="submit" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Approve Form
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let performanceTable;
        let cyclesTable;

        if ($('#performance-table').length) {
            performanceTable = $('#performance-table').DataTable({
                columnDefs: [{ orderable: false, targets: [4] }],
                pageLength: 25,
                language: {
                    lengthMenu: 'Show _MENU_ entries',
                    emptyTable: 'No performance evaluations found'
                },
                dom: 'rtip',
            });
        }

        if ($('#cycles-table').length) {
            cyclesTable = $('#cycles-table').DataTable({
                columnDefs: [{ orderable: false, targets: [3] }],
                pageLength: 25,
                language: {
                    lengthMenu: 'Show _MENU_ entries',
                    emptyTable: 'No performance cycles found'
                },
                dom: 'rtip',
            });
        }

        $('#banner-search').on('input', function () {
            const value = this.value;

            $('.tab-pane.active table').each(function () {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().search(value).draw();
                }
            });
        });

        $('#status-filter').on('change', function() {
            if (!performanceTable) return;

            const val = this.value ? '^' + this.value + '$' : '';
            performanceTable.column(3).search(val, true, false).draw();
        });

        $('#cycle-filter').on('change', function () {
            if (!performanceTable) return;

            const val = this.value ? '^' + this.value + '$' : '';
            performanceTable.column(2).search(val, true, false).draw();
        });
    </script>

    <script>
        const ipcrForms = @json($ipcrForms);

        function openCreatePerformanceCycle() {
            document.getElementById('createPerformanceCycleModal').style.display = 'flex';
        }
        function openCreateIPCRForm() {
            document.getElementById('createIPCRFormModal').style.display = 'flex';
        }
        function openBulkCreateIPCRForm() {
            document.getElementById('bulkCreateIPCRFormModal').style.display = 'flex';
        }
        function openViewIPCRForm(button) {
            const employeeId = Number(button.dataset.employeeId);
            document.getElementById('view-ipcr-sub').textContent = `For ${button.dataset.employee_name}`;

            const form = ipcrForms.find(f => Number(f.employee_id) === employeeId);
            if (!form) { console.error('No IPCR form found for employee:', employeeId); return; }

            const entries = form.entries ?? [];

            const tbody = document.getElementById('view-ipcr-table-body');
            tbody.innerHTML = '';

            const isApproved = `${form.status}` === 'Approved';

            if (entries.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No entries found for this employee.</td></tr>`;
            } else {
                entries.forEach(entry => {
                    const row = document.createElement('tr');
                    const disabledAttr = isApproved ? 'disabled' : '';
                    const readonlyAttr = isApproved ? 'readonly' : '';
                    row.innerHTML = `
                        <td style="padding:8px 12px;color:#0b044d;font-size:12.5px">${entry.kra ?? ''}</td>
                        <td style="padding:8px 12px;color:#6b6a8a;font-size:12px">${entry.objectives ?? ''}</td>
                        <td style="padding:8px 12px;color:#6b6a8a;font-size:12px">${entry.success_indicators ?? ''}</td>
                        <td style="padding:8px 12px">
                          <textarea name="entries[${entry.id}][actual_accomplishments]"
                            style="width:100%;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;font-family:inherit;color:#0b044d;resize:vertical;min-height:60px"
                            ${readonlyAttr} ${disabledAttr} required>${entry.actual_accomplishments ?? ''}</textarea>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][quality_rating]"
                                value="${entry.quality_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][efficiency_rating]"
                                value="${entry.efficiency_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][timeliness_rating]"
                                value="${entry.timeliness_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                document.querySelectorAll('.ipcr_rating').forEach(input => {
                    input.addEventListener('input', () => {
                        const max = parseInt(input.max, 10) || 5;
                        const min = parseInt(input.min, 10) || 0;
                        const val = parseInt(input.value, 10);
                        if (!isNaN(val)) {
                            if (val > max) input.value = max;
                            else if (val < min) input.value = min;
                        }
                    });

                    input.addEventListener('keypress', function(evt) {
                        if (evt.which < 48 || evt.which > 57) {
                            evt.preventDefault();
                        }
                    });
                });
            }

            document.getElementById('viewIPCRDevelopmentNeeds').textContent = form.development_needs[0].development_needs ?? "No data";
            document.getElementById('viewIPCRRecommendedTraining').textContent = form.development_needs[0].recommended_training ?? "No data";

            document.getElementById('viewIPCRFormModal').style.display = 'flex';
        }

        function openApproveIPCRForm(button) {
            const formId = Number(button.dataset.form_id);
            document.getElementById('approveIPCRTitle').textContent = `For ${button.dataset.employee_name ?? 'Employee'}`;
            document.getElementById('editIPCRFormID').value = formId;
            const url = "{{ route('performance_management.approve', ':id') }}".replace(':id', formId);
            document.getElementById('ipcr-approve-form').action = url;
            document.getElementById('approveIPCRFormModal').style.display = 'flex';
        }

        function openEditIPCRForm(button) {
            const employeeId = Number(button.dataset.employeeId);
            document.getElementById('ipcr-modal-sub').textContent = `For ${button.dataset.employeeName}`;

            const form = ipcrForms.find(f => Number(f.employee_id) === employeeId);
            if (!form) { console.error('No IPCR form found for employee:', employeeId); return; }

            const entries = form.entries ?? [];
            const submitBtn = document.getElementById('editIPCRSubmitBtn');

            if (entries.length === 0) {
                submitBtn.style.pointerEvents = 'none';
                submitBtn.classList.add('btn-export');
                submitBtn.classList.remove('modal-btn-primary');
            } else {
                submitBtn.style.pointerEvents = '';
                submitBtn.classList.remove('btn-export');
                submitBtn.classList.add('modal-btn-primary');
            }

            const url = "{{ route('performance_management.updateIPCRForm', ':id') }}".replace(':id', employeeId);
            document.getElementById('ipcr-update-form').action = url;

            const tbody = document.getElementById('ipcr-table-body');
            tbody.innerHTML = '';

            const isApproved = `${form.status}` === 'Approved';

            if (entries.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No entries found for this employee.</td></tr>`;
            } else {
                entries.forEach(entry => {
                    const row = document.createElement('tr');
                    const disabledAttr = isApproved ? 'disabled' : '';
                    const readonlyAttr = isApproved ? 'readonly' : '';
                    row.innerHTML = `
                        <td style="padding:8px 12px;color:#0b044d;font-size:12.5px">${entry.kra ?? ''}</td>
                        <td style="padding:8px 12px;color:#6b6a8a;font-size:12px">${entry.objectives ?? ''}</td>
                        <td style="padding:8px 12px;color:#6b6a8a;font-size:12px">${entry.success_indicators ?? ''}</td>
                        <td style="padding:8px 12px">
                          <textarea name="entries[${entry.id}][actual_accomplishments]"
                            style="width:100%;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;font-family:inherit;color:#0b044d;resize:vertical;min-height:60px"
                            ${readonlyAttr} ${disabledAttr} required>${entry.actual_accomplishments ?? ''}</textarea>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][quality_rating]"
                                value="${entry.quality_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][efficiency_rating]"
                                value="${entry.efficiency_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                        <td style="padding:8px 12px">
                            <input type="number" class="ipcr_rating" name="entries[${entry.id}][timeliness_rating]"
                                value="${entry.timeliness_rating ?? 0}" min="0" max="5"
                                style="width:60px;font-size:12px;padding:5px 8px;border:1.5px solid #e5e4f0;border-radius:6px;outline:none;text-align:center;font-family:inherit;color:#0b044d"
                                ${readonlyAttr} ${disabledAttr} required>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                document.querySelectorAll('.ipcr_rating').forEach(input => {
                    input.addEventListener('input', () => {
                        const max = parseInt(input.max, 10) || 5;
                        const min = parseInt(input.min, 10) || 0;
                        const val = parseInt(input.value, 10);
                        if (!isNaN(val)) {
                            if (val > max) input.value = max;
                            else if (val < min) input.value = min;
                        }
                    });

                    input.addEventListener('keypress', function(evt) {
                        if (evt.which < 48 || evt.which > 57) {
                            evt.preventDefault();
                        }
                    });
                });
            }

            document.getElementById('editIPCRFormModal').style.display = 'flex';
        }
    </script>
@endpush
