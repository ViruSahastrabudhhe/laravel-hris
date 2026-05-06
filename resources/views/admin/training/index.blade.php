@extends('layouts.admin')

@php
    $totalPrograms = $trainings->count();
    $totalParticipants = $trainings->sum('participants');
    $activePrograms = $trainings->where('status', '!=', \App\Enums\TrainingStatus::Completed->value)->count();
    $completedPrograms = $trainings->where('status', \App\Enums\TrainingStatus::Completed->value)->count();

    $typeAccents = [
        \App\Enums\TrainingType::Leadership->value  => '#0b044d',
        \App\Enums\TrainingType::SoftSkills->value  => '#d9bb00',
        \App\Enums\TrainingType::Orientation->value => '#0e7490',
        \App\Enums\TrainingType::Upskilling->value  => '#15803d',
        \App\Enums\TrainingType::Reskilling->value  => '#b45309',
        \App\Enums\TrainingType::Technical->value   => '#1d4ed8',
        \App\Enums\TrainingType::Safety->value      => '#8e1e18',
        \App\Enums\TrainingType::Service->value     => '#0f766e',
        \App\Enums\TrainingType::DEI->value         => '#7c3aed',
        \App\Enums\TrainingType::Compliance->value  => '#6b3fa0',
    ];
@endphp

@section('page-content')
<div class="stats-grid stats-grid-4" style="margin-bottom: 24px;">
    <div class="stat-card" style="--accent-color: #0b044d">
        <div class="stat-top">
            <p class="stat-label">Total Programs</p>
            <div class="stat-icon-wrap" style="background: rgba(11, 4, 77, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $totalPrograms }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">All training programs</p>
        </div>
    </div>

    <div class="stat-card" style="--accent-color: #15803d">
        <div class="stat-top">
            <p class="stat-label">Ongoing</p>
            <div class="stat-icon-wrap" style="background: rgba(21, 128, 61, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $activePrograms }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Currently active</p>
        </div>
    </div>

    <div class="stat-card" style="--accent-color: #d9bb00">
        <div class="stat-top">
            <p class="stat-label">Total Participants</p>
            <div class="stat-icon-wrap" style="background: rgba(217, 187, 0, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $totalParticipants }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">All enrollments</p>
        </div>
    </div>

    <div class="stat-card" style="--accent-color: #8e1e18">
        <div class="stat-top">
            <p class="stat-label">Completed</p>
            <div class="stat-icon-wrap" style="background: rgba(142, 30, 24, 0.1)">
                <svg width="18" height="18" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <h2 class="stat-value">{{ $completedPrograms }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Finished programs</p>
        </div>
    </div>
</div>

<div class="view-trainings" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Training Programs</p>
                <p class="table-sub">{{ config('app.name') }} · <span id="showing-count">{{ $totalPrograms }}</span> of {{ $totalPrograms }} programs</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="training-search" placeholder="Search programs..." class="search-input">
                </div>
                <select class="filter-select" id="type-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Types</option>
                    @foreach($trainingTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    @foreach($trainingStatuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
                <button class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export
                </button>
                <button onclick="openTrainingCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Training
                </button>
            </div>
        </div>
    
        <div class="table-wrapper">
            <table class="payroll-table" id="training-table">
                <thead>
                    <tr>
                        <th>Program Title</th>
                        <th>Type</th>
                        <th>Participants</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($trainings as $training)
                    @php
                        $fillPct = $training->capacity > 0 ? round(($training->participants / $training->capacity) * 100) : 0;
                        $statusClass = match($training->status) {
                            \App\Enums\TrainingStatus::Ongoing->value   => 'processed',
                            \App\Enums\TrainingStatus::Completed->value => 'on-hold',
                            \App\Enums\TrainingStatus::Canceled->value  => 'pending',
                            default                                     => 'pending',
                        };

                        $accent = $typeAccents[$training->type] ?? '#0b044d';
                    @endphp
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div>
                                    <p class="emp-name">{{ $training->program_title }}</p>
                                    <p class="emp-id">TRN-{{ str_pad($training->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-emptype" style="border-color: {{ $accent }}40; background: {{ $accent }}10; color: {{ $accent }}">{{ $training->type }}</span></td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <div style="width: 60px; height: 6px; background: #f0effe; border-radius: 99px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $fillPct }}%; background: {{ $accent }}; border-radius: 99px;"></div>
                                </div>
                                <span style="font-size: 13px; color: #0b044d; font-weight: 600;">{{ $training->participants }}</span>
                                <span style="font-size: 11px; color: #9999bb;">/ {{ $training->capacity }}</span>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($training->start_date)->format(config('app.day_month')) }}</td>
                        <td>{{ \Carbon\Carbon::parse($training->end_date)->format(config('app.day_month')) }}</td>
                        <td><span class="dept-tag">{{ $training->venue }}</span></td>
                        <td><span class="badge-status {{ $statusClass }}">{{ $training->status }}</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn-view" onclick="openTrainingViewModal(
                                    {{ $training->id }},
                                    '{{ addslashes($training->program_title) }}',
                                    '{{ addslashes($training->type) }}',
                                    {{ $training->participants }},
                                    {{ $training->capacity }},
                                    '{{ $training->start_date }}',
                                    '{{ $training->end_date }}',
                                    '{{ addslashes($training->venue) }}',
                                    '{{ addslashes($training->status) }}',
                                    '{{ $accent }}'
                                )">
                                    <svg width="12" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button onclick="openTrainingEditModal(
                                    {{ $training->id }},
                                    '{{ addslashes($training->program_title) }}',
                                    '{{ addslashes($training->type) }}',
                                    {{ $training->capacity }},
                                    '{{ $training->start_date }}',
                                    '{{ $training->end_date }}',
                                    '{{ addslashes($training->venue) }}',
                                    '{{ addslashes($training->status) }}'
                                )" class="btn-edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('trainings.destroy', $training) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this training?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
    </div>
</div>

{{-- Training View Modal --}}
<div class="modal-overlay" id="training-view-modal" style="display: none;" onclick="closeModal('training-view-modal')">
    <div class="modal-box modal-lg" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="pmodal-hero" style="display: flex; gap: 16px; align-items: flex-start;">
                <div id="modal-icon" style="width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <div>
                    <span class="modal-eyebrow" id="modal-training-id">TRAINING PROGRAM · TRN-001</span>
                    <h3 class="modal-title" id="modal-training-title">Leadership Development Program</h3>
                    <p class="modal-sub" id="modal-training-sub">Leadership Training · Municipal Hall Conference Room</p>
                    <div class="pmodal-badges" style="display: flex; gap: 8px; margin-top: 8px;">
                        <span class="badge-status" id="modal-status-badge">Ongoing</span>
                        <span class="badge-emptype" id="modal-type-badge">Leadership</span>
                    </div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('training-view-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 18px;">
                <div style="background: #f7f6ff; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #d9bb00 0%, #fbbf24 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #9999bb; margin-bottom: 2;">Participants</p>
                        <p style="font-size: 16px; font-weight: 800; color: #0b044d;"><span id="modal-participants">25</span><span style="font-size: 12px; font-weight: 500; color: #9999bb;"> / <span id="modal-capacity">30</span></span></p>
                    </div>
                </div>
                <div style="background: #f7f6ff; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; gap: 12px;">
                    <div id="capacity-icon" style="width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #9999bb; margin-bottom: 2;">Capacity Fill</p>
                        <p style="font-size: 16px; font-weight: 800;" id="modal-fill-pct">83%</p>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 16;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6;">
                    <span style="font-size: 11px; color: #9999bb; font-weight: 600; letter-spacing: 0.8px;">ENROLLMENT PROGRESS</span>
                    <span style="font-size: 11px; font-weight: 700;" id="modal-progress-pct">83%</span>
                </div>
                <div style="height: 8px; background: #f0effe; border-radius: 99px;">
                    <div id="modal-progress-bar" style="height: 100%; width: 83%; border-radius: 99px; transition: width 0.4s;"></div>
                </div>
            </div>
            <div style="font-size: 11px; color: #9999bb; font-weight: 600; letter-spacing: 0.8px; margin-bottom: 12px; padding-top: 8px; border-top: 1px solid #f0effe;">SCHEDULE & DETAILS</div>
            <div class="modal-row"><span>Start Date</span><strong id="modal-start-date">Jun 15, 2025</strong></div>
            <div class="modal-row"><span>End Date</span><strong id="modal-end-date">Jul 15, 2025</strong></div>
            <div class="modal-row"><span>Venue</span><strong id="modal-venue">Municipal Hall Conference Room</strong></div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('training-view-modal')">Close</button>
            <button class="modal-btn-primary" onclick="openParticipantsModal(currentTrainingId)">View Participants</button>
        </div>
    </div>
</div>

{{-- View Participants Modal --}}
<div class="modal-overlay" id="training-participants-modal" style="display:none" onclick="closeModal('training-participants-modal')">
    <div class="modal-box" style="width:min(600px,100%)" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">TRAINING PROGRAM</span>
                <h3 class="modal-title">Participants</h3>
                <p class="modal-sub" id="participants-modal-sub">—</p>
            </div>
            <button class="modal-close" onclick="closeModal('training-participants-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="max-height:65vh;overflow-y:auto;">
            <div id="participants-list"></div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('training-participants-modal')">Close</button>
        </div>
    </div>
</div>

{{-- Training Create Modal --}}
<div class="modal-overlay" id="training-create-modal" style="display:none" onclick="closeModal('training-create-modal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">TRAININGS</span>
                <h3 class="modal-title">Add Training Program</h3>
                <p class="modal-sub">Create a new training record</p>
            </div>
            <button class="modal-close" onclick="closeModal('training-create-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('trainings.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Program Title <span style="color:#dc2626">*</span></label>
                    <input type="text" name="program_title" placeholder="e.g. Leadership Seminar" required>
                </div>
                <div class="form-field">
                    <label>Type <span style="color:#dc2626">*</span></label>
                    <select name="type" required>
                        <option value="">Select type</option>
                        @foreach($trainingTypes as $type)
                            <option value="{{ $type->value }}">{{ $type->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Capacity <span style="color:#dc2626">*</span></label>
                    <input type="number" name="capacity" min="1" placeholder="e.g. 20" required>
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
                    <label>Venue <span style="color:#dc2626">*</span></label>
                    <input type="text" name="venue" placeholder="e.g. Municipal Hall" required>
                </div>
                <div class="form-field">
                    <label>Status <span style="color:#dc2626">*</span></label>
                    <select name="status" required>
                        <option value="">Select status</option>
                        @foreach($trainingStatuses as $status)
                            <option value="{{ $status->value }}">{{ $status->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('training-create-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Training
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Training Edit Modal --}}
<div class="modal-overlay" id="training-edit-modal" style="display:none" onclick="closeModal('training-edit-modal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">TRAININGS</span>
                <h3 class="modal-title">Edit Training</h3>
                <p class="modal-sub">Update training record</p>
            </div>
            <button class="modal-close" onclick="closeModal('training-edit-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="training-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Program Title <span style="color:#dc2626">*</span></label>
                    <input type="text" name="program_title" id="training-edit-title" required>
                </div>
                <div class="form-field">
                    <label>Type <span style="color:#dc2626">*</span></label>
                    <select name="type" id="training-edit-type" required>
                        <option value="">Select type</option>
                        @foreach($trainingTypes as $type)
                            <option value="{{ $type->value }}">{{ $type->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Capacity <span style="color:#dc2626">*</span></label>
                    <input type="number" name="capacity" id="training-edit-capacity" min="1" required>
                </div>
                <div class="form-field">
                    <label>Start Date <span style="color:#dc2626">*</span></label>
                    <input type="date" name="start_date" id="training-edit-start" required>
                </div>
                <div class="form-field">
                    <label>End Date <span style="color:#dc2626">*</span></label>
                    <input type="date" name="end_date" id="training-edit-end" required>
                </div>
                <div class="form-field">
                    <label>Venue <span style="color:#dc2626">*</span></label>
                    <input type="text" name="venue" id="training-edit-venue" required>
                </div>
                <div class="form-field">
                    <label>Status <span style="color:#dc2626">*</span></label>
                    <select name="status" id="training-edit-status" required>
                        <option value="">Select status</option>
                        @foreach($trainingStatuses as $status)
                            <option value="{{ $status->value }}">{{ $status->value }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="status" id="training-edit-status-hidden">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('training-edit-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Training
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        if ($('#training-table').length) {
            const trainingTable = $('#training-table').DataTable({
                columnDefs: [{ orderable: false, targets: [7] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No trainings found' },
                dom: 'rtip',
            });

            $('#training-search').on('keyup', function() {
                trainingTable.search(this.value).draw();
            });

            $('#type-filter').on('change', function() {
                const value = $(this).val();
                trainingTable.column(1).search(value ? '^' + escapeRegex(value) + '$' : '', true, false).draw();
            });

            $('#status-filter').on('change', function() {
                const value = $(this).val();
                trainingTable.column(6).search(value ? '^' + escapeRegex(value) + '$' : '', true, false).draw();
            });
        }
    </script>

    <script>
        let currentTrainingId = null;

        const statusColors = {
            'Pending':   { bg: '#fefce8', color: '#d9bb00' },
            'Enrolled':  { bg: '#f0fdf4', color: '#15803d' },
            'Completed': { bg: '#f0effe', color: '#0b044d' },
            'Declined':  { bg: '#fff1f2', color: '#8e1e18' },
            'Failed':    { bg: '#fff1f2', color: '#8e1e18' },
            'Canceled':  { bg: '#fefce8', color: '#d9bb00' },
        };

        function openTrainingViewModal(id, title, type, participants, capacity, startDate, endDate, venue, status, accent) {
            currentTrainingId = id;
            const fillPct = capacity > 0 ? Math.round((participants / capacity) * 100) : 0;

            document.getElementById('modal-training-id').textContent = 'TRAINING PROGRAM · TRN-' + String(id).padStart(3, '0');
            document.getElementById('modal-training-title').textContent = title;
            document.getElementById('modal-training-sub').textContent = type + ' · ' + venue;
            const statusClasses = {
                '{{ \App\Enums\TrainingStatus::Ongoing->value }}':   'processed',
                '{{ \App\Enums\TrainingStatus::Completed->value }}': 'on-hold',
                '{{ \App\Enums\TrainingStatus::Canceled->value }}':  'pending',
                '{{ \App\Enums\TrainingStatus::Scheduled->value }}': 'pending',
            };
            const statusBadge = document.getElementById('modal-status-badge');
            statusBadge.textContent = status;
            statusBadge.className = 'badge-status ' + (statusClasses[status] ?? 'pending');
            document.getElementById('modal-type-badge').textContent = type;
            document.getElementById('modal-type-badge').style.color = accent;
            document.getElementById('modal-type-badge').style.background = accent + '18';
            document.getElementById('modal-type-badge').style.borderColor = accent + '40';
            document.getElementById('modal-icon').style.background = accent;
            document.getElementById('capacity-icon').style.background = accent;
            document.getElementById('modal-participants').textContent = participants;
            document.getElementById('modal-capacity').textContent = capacity;
            document.getElementById('modal-fill-pct').textContent = fillPct + '%';
            document.getElementById('modal-fill-pct').style.color = accent;
            document.getElementById('modal-progress-pct').textContent = fillPct + '%';
            document.getElementById('modal-progress-bar').style.width = fillPct + '%';
            document.getElementById('modal-progress-bar').style.background = accent;
            document.getElementById('modal-start-date').textContent = startDate;
            document.getElementById('modal-end-date').textContent = endDate;
            document.getElementById('modal-venue').textContent = venue;
            document.getElementById('training-view-modal').style.display = 'flex';
        }

        function openTrainingCreateModal() { document.getElementById('training-create-modal').style.display = 'flex'; }

        function openTrainingEditModal(id, title, type, capacity, startDate, endDate, venue, status) {
            var url = "{{ route('trainings.update', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('training-edit-form').action = url;
            document.getElementById('training-edit-title').value = title;
            document.getElementById('training-edit-type').value = type;
            document.getElementById('training-edit-capacity').value = capacity;
            document.getElementById('training-edit-start').value = startDate;
            document.getElementById('training-edit-end').value = endDate;
            document.getElementById('training-edit-venue').value = venue;
            document.getElementById('training-edit-status').value = status;
            document.getElementById('training-edit-modal').style.display = 'flex';

            const statusSelect = document.getElementById('training-edit-status');
            const isCompleted = status === '{{ \App\Enums\TrainingStatus::Completed->value }}';
            statusSelect.disabled = isCompleted;
            statusSelect.style.opacity = isCompleted ? '0.5' : '1';
            statusSelect.style.cursor = isCompleted ? 'not-allowed' : '';
            document.getElementById('training-edit-status-hidden').disabled = !isCompleted;
            document.getElementById('training-edit-status-hidden').value = isCompleted ? status : '';
        }

        function openParticipantsModal(trainingId) {
            const list = document.getElementById('participants-list');
            const sub  = document.getElementById('participants-modal-sub');
            list.innerHTML = '<p style="text-align:center;color:#9999bb;padding:24px 0;">Loading...</p>';
            sub.textContent = document.getElementById('modal-training-title').textContent;
            document.getElementById('training-participants-modal').style.display = 'flex';

            fetch(`/admin/trainings/${trainingId}/participants`)
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        list.innerHTML = '<p style="text-align:center;color:#9999bb;padding:24px 0;">No participants enrolled.</p>';
                        return;
                    }
                    list.innerHTML = data.map(p => {
                        const sc = statusColors[p.status] ?? { bg: '#f7f6ff', color: '#6b6a8a' };
                        const isPending = p.status === 'Pending';
                        return `<div id="participant-row-${p.id}" style="padding:12px 0;border-bottom:1px solid #f0effe;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <p style="font-size:13px;font-weight:600;color:#0b044d;margin:0">${p.name}</p>
                                    <p id="participant-remarks-${p.id}" style="font-size:11px;color:#9999bb;margin:2px 0 0">${p.remarks ?? ''}</p>
                                </div>
                                <div id="participant-actions-${p.id}" style="display:flex;align-items:center;gap:8px;">
                                    <span id="participant-badge-${p.id}" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;background:${sc.bg};color:${sc.color}">${p.status}</span>
                                    ${isPending ? `
                                        <button onclick="submitApprove(${p.id})" style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:7px;border:1.5px solid #86efac;background:#f0fdf4;color:#15803d;cursor:pointer;">Approve</button>
                                        <button onclick="toggleDeclineForm(${p.id})" style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:7px;border:1.5px solid #fca5a5;background:#fff1f2;color:#8e1e18;cursor:pointer;">Decline</button>
                                    ` : ''}
                                </div>
                            </div>
                            ${isPending ? `
                            <div id="decline-form-${p.id}" style="display:none;margin-top:10px;background:#fff8f8;border-radius:8px;padding:10px 12px;border:1px solid #fca5a5;">
                                <p style="font-size:11px;font-weight:700;color:#8e1e18;margin:0 0 6px;letter-spacing:0.5px;">DECLINE REASON</p>
                                <textarea id="decline-remarks-${p.id}" placeholder="Enter reason for declining..." style="width:100%;font-size:12px;padding:7px 10px;border:1.5px solid #fca5a5;border-radius:7px;resize:none;outline:none;font-family:inherit;color:#0b044d;box-sizing:border-box;" rows="2"></textarea>
                                <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
                                    <button onclick="toggleDeclineForm(${p.id})" style="font-size:12px;font-weight:600;padding:5px 12px;border-radius:7px;border:1.5px solid #dddcf0;background:#fff;color:#6b6a8a;cursor:pointer;">Cancel</button>
                                    <button onclick="submitDecline(${p.id})" style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:7px;border:none;background:#8e1e18;color:#fff;cursor:pointer;">Confirm Decline</button>
                                </div>
                            </div>` : ''}
                        </div>`;
                    }).join('');
                });
        }

        function toggleDeclineForm(id) {
            const form = document.getElementById(`decline-form-${id}`);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function submitApprove(id) {
            fetch(`/admin/trainings/${id}/approve`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                const sc = statusColors['Enrolled'];
                document.getElementById(`participant-badge-${id}`).textContent = 'Enrolled';
                document.getElementById(`participant-badge-${id}`).style.background = sc.bg;
                document.getElementById(`participant-badge-${id}`).style.color = sc.color;
                document.getElementById(`participant-actions-${id}`).querySelectorAll('button').forEach(b => b.remove());
                document.getElementById(`decline-form-${id}`)?.remove();
            });
        }

        function submitDecline(id) {
            const remarks = document.getElementById(`decline-remarks-${id}`).value;
            fetch(`/admin/trainings/${id}/decline`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ remarks }),
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                const sc = statusColors['Declined'];
                document.getElementById(`participant-badge-${id}`).textContent = 'Declined';
                document.getElementById(`participant-badge-${id}`).style.background = sc.bg;
                document.getElementById(`participant-badge-${id}`).style.color = sc.color;
                document.getElementById(`participant-remarks-${id}`).textContent = remarks;
                document.getElementById(`participant-actions-${id}`).querySelectorAll('button').forEach(b => b.remove());
                document.getElementById(`decline-form-${id}`).remove();
            });
        }
    </script>
@endpush