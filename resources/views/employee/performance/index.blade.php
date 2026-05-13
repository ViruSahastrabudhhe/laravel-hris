@extends('layouts.employee')

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div>
            <h2>Performance Overview</h2>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="perf-search" placeholder="Search evaluations..." class="recruit-search" oninput="filterEvalTable(this.value)">
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Latest Rating</p>
            <div class="stat-icon-wrap" style="background:#0b044d15">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
        </div>
        <p class="stat-value">—</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Latest evaluation</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Average Rating</p>
            <div class="stat-icon-wrap" style="background:#15803d15">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 18"/></svg>
            </div>
        </div>
        <p class="stat-value">—</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">All evaluations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Evaluations</p>
            <div class="stat-icon-wrap" style="background:#d9bb0015">
                <svg width="17" height="17" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Completed reviews</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Goals Achieved</p>
            <div class="stat-icon-wrap" style="background:#8e1e1815">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Total goals</p>
        </div>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">IPCR Forms</p>
            <p class="table-sub">Your IPCR Forms</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="eval-table">
            <thead>
                <tr>
                    <th>Form ID</th>
                    <th>Employee</th>
                    <th>Performance Cycle</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($ipcrForms as $form)
                <tr>
                    <td>{{$form->id}}</td>
                    <td>{{$form->employee->first_name}} {{$form->employee->last_name}}</td>
                    <td>{{$form->cycle->start_date}} - {{$form->cycle->end_date}}</td>
                    <td>{{$form->status}}</td>
                    <td>
                        <div class="row-actions">
                            <button class="btn-view">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button class="btn-edit" onclick="openEditIPCRFormModal(this)"
                                data-ipcr_form_id="{{$form->id}}"
                            >
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Evaluation View Modal --}}
<div class="perf-modal-overlay" id="evalModal" onclick="closeEvalModal()">
    <div class="perf-modal-box" onclick="event.stopPropagation()">
        <div class="perf-modal-header">
            <div class="pmodal-hero">
                <div class="pmodal-hero-icon" id="evalIcon" style="background:linear-gradient(135deg,#0b044d,#1a0f6e)">
                    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <span class="modal-eyebrow" id="evalId">PERFORMANCE EVALUATION</span>
                    <h3 class="modal-title">Evaluation Report</h3>
                    <p class="modal-sub" id="evalSub">Period · Completed on Date</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeEvalModal()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="perf-modal-body" id="evalBody"></div>
        <div class="perf-modal-footer">
            <button class="modal-btn-ghost" onclick="closeEvalModal()">Close</button>
            <button class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Report
            </button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="editIPCRFormModal" style="display: none;">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EDIT IPCR FORM</span>
                <h3 class="modal-title">Input KRAs, Objectives, etc.</h3>
            </div>
            <button class="modal-close" onclick="closeModal('editIPCRFormModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="max-height:60vh;overflow:auto;">
            <form action="{{ route('my_performance.storeIPCREntry') }}" method="POST">
                @csrf
                <!-- Main Form Identifier -->
                <div class="form-field">
                    <label>IPCR Form ID: <span style="color:#dc2626" id="editIPCRFormIDSpan">*</span></label>
                    <input type="hidden" name="ipcr_form_id" id="editIPCRFormID" readonly placeholder="IPCR Form ID"><br>
                </div>

                <table id="kraTable" border="1" style="width: 100%; text-align: left; margin-bottom: 15px;">
                    <thead>
                    <tr>
                        <th>KRA</th>
                        <th>Objectives</th>
                        <th>Success Indicators</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Row 1 (Default) -->
                    <tr>
                        <td><input type="text" name="kra[]" placeholder="KRA" required></td>
                        <td><input type="text" name="objectives[]" placeholder="Objectives" required></td>
                        <td><input type="text" name="success_indicators[]" placeholder="Success Indicators" required></td>
                        <td><button type="button" onclick="removeRow(this)">Delete</button></td>
                    </tr>
                    </tbody>
                </table>

                <!-- Control Buttons -->
                <button type="button" id="addRowBtn">Add New Row</button>
                <button type="submit">Submit All Rows</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEditIPCRFormModal(button) {
        const data = button.dataset;

        document.getElementById('editIPCRFormID').value = data.ipcr_form_id;
        document.getElementById('editIPCRFormIDSpan').textContent = data.ipcr_form_id;
        document.getElementById('editIPCRFormModal').style.display = 'flex';
    }
</script>

<script>
    document.getElementById('addRowBtn').addEventListener('click', function() {
        // Reference the table body
        const tbody = document.getElementById('kraTable').getElementsByTagName('tbody')[0];

        // Create a new row element
        const newRow = document.createElement('tr');

        // Define the HTML structure for the new row fields
        newRow.innerHTML = `
                <td><input type="text" name="kra[]" placeholder="KRA" required></td>
                <td><input type="text" name="objectives[]" placeholder="Objectives" required></td>
                <td><input type="text" name="success_indicators[]" placeholder="Success Indicators" required></td>
                <td><button type="button" onclick="removeRow(this)">Delete</button></td>
            `;

        // Append the row to the table body
        tbody.appendChild(newRow);
    });

    // Function to delete a row if the user changes their mind
    function removeRow(button) {
        const row = button.closest('tr');
        const tbody = row.parentNode;

        // Prevent deleting the last remaining row if you want to enforce at least one entry
        if (tbody.rows.length > 1) {
            row.remove();
        } else {
            alert("You must keep at least one row.");
        }
    }
</script>

<script>
    function filterEvalTable(query) {
        const q = query.toLowerCase();
        const rows = document.querySelectorAll('#eval-table-body tr');
        let count = 0;
        rows.forEach(row => {
            const show = row.textContent.toLowerCase().includes(q);
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });
        document.getElementById('eval-count').textContent = count;
    }

    function openEvaluation(id, period, rating, completedDate, evaluator, feedback, strengths, improvements) {
        const ratingColor = rating >= 4.5 ? '#15803d' : rating >= 4.0 ? '#d9bb00' : '#8e1e18';
        document.getElementById('evalId').textContent = 'PERFORMANCE EVALUATION · ' + id;
        document.getElementById('evalSub').textContent = period + ' · Completed on ' + completedDate;
        document.getElementById('evalIcon').style.background = 'linear-gradient(135deg,' + ratingColor + ',' + ratingColor + '99)';

        const strengthsHtml = strengths.map(s => '<span class="perf-tag" style="background:#15803d15;color:#15803d">' + s + '</span>').join('');
        const improvementsHtml = improvements.map(i => '<span class="perf-tag" style="background:#d9bb0015;color:#a16207">' + i + '</span>').join('');

        document.getElementById('evalBody').innerHTML =
            '<div class="perf-rating-box"><div class="perf-rating-icon" style="background:linear-gradient(135deg,' + ratingColor + ',' + ratingColor + '99)"><span>' + rating + '</span></div><div><p class="perf-rating-label">OVERALL RATING</p><p class="perf-rating-value">' + rating + ' out of 5.0</p></div></div>' +
            '<span class="modal-section-label">EVALUATION DETAILS</span>' +
            '<div class="modal-row"><span>Period</span><strong>' + period + '</strong></div>' +
            '<div class="modal-row"><span>Evaluator</span><strong>' + evaluator + '</strong></div>' +
            '<div class="modal-row"><span>Completed</span><strong>' + completedDate + '</strong></div>' +
            '<span class="modal-section-label" style="margin-top:16px">FEEDBACK</span>' +
            '<p style="font-size:13px;color:#6b6a8a;line-height:1.6;margin-bottom:16px">' + feedback + '</p>' +
            '<span class="modal-section-label">STRENGTHS</span>' +
            '<div class="perf-tags">' + strengthsHtml + '</div>' +
            '<span class="modal-section-label">AREAS FOR IMPROVEMENT</span>' +
            '<div class="perf-tags">' + improvementsHtml + '</div>';

        document.getElementById('evalModal').classList.add('show');
    }

    function closeEvalModal() {
        document.getElementById('evalModal').classList.remove('show');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEvalModal();
    });
</script>
@endpush
