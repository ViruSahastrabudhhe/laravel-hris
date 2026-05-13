@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
@endphp

@push('styles')
    @vite('resources/css/admin/adminPerformance.css')
@endpush

@section('page-content')
    <h1>IPCR Performance Dashboard</h1>

    <button class="modal-btn-primary" type="button" onclick="openCreatePerformanceCycle()">
        Create Performance Cycle
    </button>

    <button class="modal-btn-primary" type="button" onclick="openCreateIPCRForm()">
        Create IPCR Form
    </button>

    <button class="modal-btn-primary" type="button" onclick="openBulkCreateIPCRForm()">
        Bulk Create IPCR Form
    </button>

    <div>
        <h3>Total IPCR Forms: {{ $ipcrForms->count() }}</h3>
    </div>

    <table class="payroll-table">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Position</th>
            <th>Status</th>
            <th>Final Rating</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($ipcrForms as $form)
            <tr>
                <td>{{ $form->employee->first_name }} {{ $form->employee->last_name }}</td>
                <td>{{ $form->employee->position->title }}</td>
                <td>{{ $form->status }}</td>
                <td>{{ $form->final_rating ?? 'N/A' }}</td>
                <td>
                    <div class="row-actions">
                        <button class="btn-view" onclick="openEditIPCRForm(this)"
                            data-employee-id="{{ $form->employee->id }}"
                            data-employee-name="{{ $form->employee->first_name }} {{ $form->employee->last_name }}"
                        >
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form action="{{ route('performance_management.computeFinalRating', $form->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="btn-edit" type="submit">Compute</button>
                        </form>
                        <form action="{{ route('performance_management.approve', $form->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="btn-success" type="submit">Approve</button>
                        </form>
                        <form action="{{ route('performance_management.destroy', $form->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn-danger" type="submit">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Create Performance Cycle Modal --}}
    <div class="modal-overlay" id="createPerformanceCycleModal" style="display: none;">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">CREATE PERFORMANCE CYCLE</span>
                    <h3 class="modal-title">Create Active Cycle</h3>
                </div>
                <button class="modal-close" onclick="closeModal('createPerformanceCycleModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('performance_management.storePerformanceCycle') }}" method="post">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    <div class="form-field">
                        <label>Name <span style="color:#dc2626">*</span></label>
                        <input type="text" name="name" required>
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
                        <label style="display:flex;align-items:center;gap:8px">
                            <input type="checkbox" name="is_active" value="1" checked style="width:auto">
                            <span>Active Cycle</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('createPerformanceCycleModal')">Cancel</button>
                    <button type="submit" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Create Cycle
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create IPCREntry Form Modal --}}
    <div class="modal-overlay" id="createIPCRFormModal" style="display: none;">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">CREATE IPCR FORM</span>
                    <h3 class="modal-title">Create IPCR Form For Employee</h3>
                </div>
                <button class="modal-close" onclick="closeModal('createIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('performance_management.storeIPCRForm') }}" method="post">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    @if($cycles->isEmpty())
                        <div style="text-align: center; padding: 2rem 0; ">
                            <p style="margin-bottom: 1rem; color: #4b5563;">You need to create a Performance Cycle before creating an IPCR form.</p>
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
                    @if($cycles->isNotEmpty())
                        <button type="submit" class="modal-btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            Create Form
                        </button>
                    @else
                        <button type="button" class="modal-btn-primary" onclick="closeModal('createIPCRFormModal'); openModal('createPerformanceCycleModal')">
                            Create Performance Cycle
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Create IPCREntry Form Modal --}}
    <div class="modal-overlay" id="bulkCreateIPCRFormModal" style="display: none;">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">BULK CREATE IPCR FORM</span>
                    <h3 class="modal-title">Bulk Create IPCR Forms</h3>
                </div>
                <button class="modal-close" onclick="closeModal('bulkCreateIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('performance_management.bulkStoreIPCRForm') }}" method="post">
                @csrf
                <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                    @if($cycles->isEmpty())
                        <div style="text-align: center; padding: 2rem 0; ">
                            <p style="margin-bottom: 1rem; color: #4b5563;">You need to create a Performance Cycle before creating an IPCR form.</p>
                        </div>
                    @else
                        <h1 id="qr-modal-sub" style="font-size:25px;color:#0b044d;font-weight:600;margin:0">For All Employees</h1>
                        <p style="font-size:12px;color:#6b6a8a;margin:6px 0 0">IPCR forms will be created for every employee.</p>
                        <div style="margin-top: 1.5rem;" class="form-field">
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
                    @if($cycles->isNotEmpty())
                        <button type="submit" class="modal-btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            Bulk Create Form
                        </button>
                    @else
                        <button type="button" class="modal-btn-primary" onclick="closeModal('bulkCreateIPCRFormModal'); openModal('createPerformanceCycleModal')">
                            Create Performance Cycle
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Edit IPCREntry Form Modal --}}
    <div class="modal-overlay" id="editIPCRFormModal" style="display:none">
        <div class="modal-box" style="max-width:1100px" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <span class="modal-eyebrow">IPCR FORM</span>
                    <h3 class="modal-title" id="ipcr-modal-title">View & Update Entries</h3>
                    <p class="modal-sub" id="ipcr-modal-sub">For Employee Name</p>
                </div>
                <button class="modal-close" onclick="closeModal('editIPCRFormModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="ipcr-update-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height:65vh;overflow-y:auto;">

                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                        <tr style="background:#f7f6ff;text-align:left;">
                            <th style="padding:10px;">KRA</th>
                            <th style="padding:10px;">Objectives</th>
                            <th style="padding:10px;">Success Indicators</th>
                            <th style="padding:10px;">Actual Accomplishments</th>
                            <th style="padding:10px;">Quality</th>
                            <th style="padding:10px;">Efficiency</th>
                            <th style="padding:10px;">Timeliness</th>
                        </tr>
                        </thead>

                        <tbody id="ipcr-table-body">
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('editIPCRFormModal')">
                        Cancel
                    </button>
                    <button type="submit" class="modal-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
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
        function openEditIPCRForm(button) {
            const employeeId = Number(button.dataset.employeeId);

            document.getElementById('ipcr-modal-sub').textContent = `For ${button.dataset.employeeName}`;
            const form = ipcrForms.find(f => Number(f.employee_id) === employeeId);

            if (!form) {
                console.error('No IPCREntry form found for employee:', employeeId);
                return;
            }

            const entries = form.entries ?? [];

            var url = "{{ route('performance_management.updateIPCRForm', ':id') }}".replace(':id', employeeId);
            document.getElementById('ipcr-update-form').action = url;

            const tbody = document.getElementById('ipcr-table-body');
            tbody.innerHTML = '';

            entries.forEach(entry => {
                tbody.innerHTML += `
                    <tr>
                        <td>${entry.kra ?? ''}</td>
                        <td>${entry.objectives ?? ''}</td>
                        <td>${entry.success_indicators ?? ''}</td>

                        <td>
                            <input type="text"
                                name="entries[${entry.id}][actual_accomplishments]"
                                value="${entry.actual_accomplishments ?? ''}">
                        </td>

                        <td>
                            <input type="number"
                                name="entries[${entry.id}][quality_rating]"
                                value="${entry.quality_rating ?? 0}">
                        </td>

                        <td>
                            <input type="number"
                                name="entries[${entry.id}][efficiency_rating]"
                                value="${entry.efficiency_rating ?? 0}">
                        </td>

                        <td>
                            <input type="number"
                                name="entries[${entry.id}][timeliness_rating]"
                                value="${entry.timeliness_rating ?? 0}">
                        </td>
                    </tr>
                `;
            });

            document.getElementById('editIPCRFormModal').style.display = 'flex';
        }
    </script>
@endpush
