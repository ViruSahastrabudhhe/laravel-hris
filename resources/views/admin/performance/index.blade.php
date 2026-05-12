@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
$performance = collect([
    ['id' => 'PGS-0041', 'name' => 'Maria B. Santos',   'position' => 'Administrative Officer IV',   'dept' => 'Office of the Mayor',             'period' => 'Jan-Jun 2025', 'rating' => 4.8, 'status' => 'Completed', 'evaluator' => 'Mayor Office',       'dueDate' => 'Jun 30, 2025'],
    ['id' => 'PGS-0082', 'name' => 'Juan P. dela Cruz', 'position' => 'Municipal Engineer II',        'dept' => 'Office of the Mun. Engineer',      'period' => 'Jan-Jun 2025', 'rating' => 4.5, 'status' => 'Completed', 'evaluator' => 'Municipal Engineer', 'dueDate' => 'Jun 30, 2025'],
    ['id' => 'PGS-0115', 'name' => 'Ana R. Reyes',      'position' => 'Nurse II',                    'dept' => 'Municipal Health Office',          'period' => 'Jan-Jun 2025', 'rating' => 4.9, 'status' => 'Completed', 'evaluator' => 'Health Officer',     'dueDate' => 'Jun 30, 2025'],
    ['id' => 'PGS-0203', 'name' => 'Carlos M. Mendoza', 'position' => 'Municipal Treasurer III',     'dept' => 'Office of the Mun. Treasurer',     'period' => 'Jan-Jun 2025', 'rating' => 4.6, 'status' => 'Completed', 'evaluator' => 'Municipal Treasurer','dueDate' => 'Jun 30, 2025'],
    ['id' => 'PGS-0267', 'name' => 'Liza G. Gomez',     'position' => 'Social Welfare Officer II',   'dept' => 'MSWD – Pagsanjan',                 'period' => 'Jan-Jun 2025', 'rating' => null,'status' => 'Pending',   'evaluator' => 'MSWD Head',          'dueDate' => 'Jun 30, 2025'],
    ['id' => 'PGS-0310', 'name' => 'Roberto T. Flores', 'position' => 'Municipal Civil Registrar I', 'dept' => 'Municipal Civil Registrar',        'period' => 'Jan-Jun 2025', 'rating' => null,'status' => 'Pending',   'evaluator' => 'Civil Registrar',    'dueDate' => 'Jun 30, 2025'],
]);
$avatarColors    = ['#0b044d','#8e1e18','#1a0f6e','#5a0f0b','#2d1a8e','#6b3fa0'];
$departments     = $performance->pluck('dept')->unique()->values();
$totalEvaluations = $performance->count();
$completedCount  = $performance->where('status','Completed')->count();
$pendingCount    = $performance->where('status','Pending')->count();
$avgRating       = round($performance->whereNotNull('rating')->avg('rating') ?? 0, 1);

if (!function_exists('perfInitials')) {
    function perfInitials($name) {
        $parts = explode(' ', $name);
        $i = '';
        foreach ($parts as $p) { if (preg_match('/^[A-Z]/', $p)) $i .= $p[0]; }
        return strtoupper(substr($i, 0, 2));
    }
}
@endphp

@push('styles')
    @vite('resources/css/admin/adminPerformance.css')
@endpush

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
            <h2>Performance Management</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Employee Evaluations</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="banner-search" placeholder="Search evaluations..." class="recruit-search" oninput="filterPerformance(this.value)">
        </div>
    </div>
</div>

<div class="stats-grid stats-grid-4" style="margin-bottom:24px">
    <div class="stat-card" style="--accent-color:#0b044d">
        <div class="stat-top">
            <p class="stat-label">Total Evaluations</p>
            <div class="stat-icon-wrap" style="background:rgba(11,4,77,0.1)">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalEvaluations }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#0b044d"></span><p class="stat-sub">All employees</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#15803d">
        <div class="stat-top">
            <p class="stat-label">Completed</p>
            <div class="stat-icon-wrap" style="background:rgba(21,128,61,0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $completedCount }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#15803d"></span><p class="stat-sub">Finished evaluations</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#d9bb00">
        <div class="stat-top">
            <p class="stat-label">Pending</p>
            <div class="stat-icon-wrap" style="background:rgba(217,187,0,0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $pendingCount }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#d9bb00"></span><p class="stat-sub">Awaiting evaluation</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#6b3fa0">
        <div class="stat-top">
            <p class="stat-label">Average Rating</p>
            <div class="stat-icon-wrap" style="background:rgba(107,63,160,0.1)">
                <svg width="18" height="18" fill="none" stroke="#6b3fa0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $avgRating }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#6b3fa0"></span><p class="stat-sub">Out of 5.0</p></div>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Performance Evaluations</p>
            <p class="table-sub">{{ config('app.name') }} &nbsp;·&nbsp; <span id="showing-count">{{ $totalEvaluations }}</span> of {{ $totalEvaluations }} evaluations</p>
        </div>
        <div class="table-actions">
            <select class="filter-select" id="dept-filter">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <select class="filter-select" id="status-filter">
                <option value="">All Status</option>
                <option value="Completed">Completed</option>
                <option value="Pending">Pending</option>
            </select>
            <button class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </button>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="performance-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Period</th>
                    <th>Evaluator</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="performance-table-body">
                @foreach($performance as $index => $perf)
                @php $color = $avatarColors[$index % count($avatarColors)]; @endphp
                <tr data-dept="{{ $perf['dept'] }}" data-status="{{ $perf['status'] }}">
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ $color }}">{{ perfInitials($perf['name']) }}</div>
                            <div>
                                <p class="emp-name">{{ $perf['name'] }}</p>
                                <p class="emp-id">{{ $perf['id'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="position-cell">{{ $perf['position'] }}</span></td>
                    <td><span class="dept-tag">{{ $perf['dept'] }}</span></td>
                    <td><span class="perf-period">{{ $perf['period'] }}</span></td>
                    <td><span class="perf-evaluator">{{ $perf['evaluator'] }}</span></td>
                    <td>
                        @if($perf['rating'])
                            <div class="star-wrap">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            fill="{{ $i <= round($perf['rating']) ? '#6b3fa0' : '#e4e3f0' }}"
                                            stroke="{{ $i <= round($perf['rating']) ? '#6b3fa0' : '#e4e3f0' }}"
                                            stroke-width="1">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span style="font-size:13px;color:#0b044d;font-weight:600">{{ $perf['rating'] }}</span>
                            </div>
                        @else
                            <span style="font-size:12.5px;color:#9999bb">Not rated</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status {{ $perf['status'] === 'Completed' ? 'processed' : 'pending' }}">
                            {{ $perf['status'] }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="btn-view" onclick="viewPerformance('{{ $perf['id'] }}')">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            @if($perf['status'] === 'Pending')
                                <button class="btn-evaluate" onclick="showEvaluateModal('{{ $perf['id'] }}')">Evaluate</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="perf-empty" id="empty-state">
            <p>No evaluations found</p>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div class="modal-overlay" id="view-modal" style="display:none" onclick="if(event.target===this)closeModal('view-modal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow" id="modal-perf-id">PERFORMANCE EVALUATION</span>
                <h3 class="modal-title" id="modal-perf-name">Employee Name</h3>
                <p class="modal-sub" id="modal-perf-position">Position · Department</p>
            </div>
            <button class="modal-close" onclick="closeModal('view-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="perf-modal-hero">
                <div class="emp-avatar" id="modal-avatar" style="width:48px;height:48px;border-radius:12px;font-size:16px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0">MS</div>
                <div>
                    <p class="perf-modal-empid" id="modal-emp-id">PGS-0000</p>
                    <span class="badge-status" id="modal-status-badge">Completed</span>
                </div>
            </div>
            <div class="modal-row"><span>Employee</span><strong id="modal-name">—</strong></div>
            <div class="modal-row"><span>Position</span><strong id="modal-position">—</strong></div>
            <div class="modal-row"><span>Department</span><strong id="modal-dept">—</strong></div>
            <div class="modal-row"><span>Evaluation Period</span><strong id="modal-period">—</strong></div>
            <div class="modal-row"><span>Evaluator</span><strong id="modal-evaluator">—</strong></div>
            <div class="modal-row"><span>Due Date</span><strong id="modal-dueDate">—</strong></div>
            <div class="modal-row"><span>Overall Rating</span><strong id="modal-rating" style="color:#6b3fa0">—</strong></div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('view-modal')">Close</button>
            <button class="modal-btn-primary" id="modal-action-btn">View Full Report</button>
        </div>
    </div>
</div>

{{-- Evaluate Modal --}}
<div class="modal-overlay" id="evaluate-modal" style="display:none" onclick="if(event.target===this)closeModal('evaluate-modal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EVALUATE PERFORMANCE</span>
                <h3 class="modal-title" id="eval-name">Employee Name</h3>
                <p class="modal-sub" id="eval-position">Position</p>
            </div>
            <button class="modal-close" onclick="closeModal('evaluate-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="eval-field">
                <label>Overall Rating (1.0 – 5.0)</label>
                <input type="number" id="eval-rating" step="0.1" min="1" max="5" value="4.0">
            </div>
            <div class="eval-field">
                <label>Performance Comments</label>
                <textarea id="eval-comments" rows="4" placeholder="Enter performance comments..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('evaluate-modal')">Cancel</button>
            <button class="modal-btn-primary" onclick="submitEvaluation()">Submit Evaluation</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const performanceData = @json($performance);
const avatarColors    = @json($avatarColors);
let currentEvalId     = null;

function getInitials(name) {
    return name.split(' ').filter(n => /^[A-Z]/.test(n)).map(p => p[0]).join('').slice(0,2).toUpperCase();
}

/* ── Filtering ── */
function filterPerformance(q) {
    const query  = (q || '').toLowerCase();
    const dept   = document.getElementById('dept-filter').value;
    const status = document.getElementById('status-filter').value;
    let count = 0;
    document.querySelectorAll('#performance-table-body tr').forEach(row => {
        const text        = row.textContent.toLowerCase();
        const matchQuery  = !query  || text.includes(query);
        const matchDept   = !dept   || (row.dataset.dept   || '') === dept;
        const matchStatus = !status || (row.dataset.status || '') === status;
        const show = matchQuery && matchDept && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('showing-count').textContent = count;
    document.getElementById('visible-count').textContent = count;
    document.getElementById('empty-state').style.display = count === 0 ? 'block' : 'none';
}

document.getElementById('dept-filter').addEventListener('change',   () => filterPerformance(document.getElementById('banner-search').value));
document.getElementById('status-filter').addEventListener('change', () => filterPerformance(document.getElementById('banner-search').value));

/* ── DataTable (sorting only) ── */
$(function () {
    $('#performance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [7] }],
        pageLength: 25,
        language: { lengthMenu: 'Show _MENU_ entries', emptyTable: 'No evaluations found' },
        dom: 'rtip',
    });
});

/* ── View modal ── */
function viewPerformance(perfId) {
    const perf = performanceData.find(p => p.id === perfId);
    if (!perf) return;
    const idx   = performanceData.findIndex(p => p.id === perfId);
    const color = avatarColors[idx % avatarColors.length];

    document.getElementById('modal-perf-id').textContent       = 'PERFORMANCE EVALUATION · ' + perf.id;
    document.getElementById('modal-perf-name').textContent     = perf.name;
    document.getElementById('modal-perf-position').textContent = perf.position + ' · ' + perf.dept;
    document.getElementById('modal-avatar').style.background   = color;
    document.getElementById('modal-avatar').textContent        = getInitials(perf.name);
    document.getElementById('modal-emp-id').textContent        = perf.id;

    const badge = document.getElementById('modal-status-badge');
    badge.textContent = perf.status;
    badge.className   = 'badge-status ' + (perf.status === 'Completed' ? 'processed' : 'pending');

    document.getElementById('modal-name').textContent      = perf.name;
    document.getElementById('modal-position').textContent  = perf.position;
    document.getElementById('modal-dept').textContent      = perf.dept;
    document.getElementById('modal-period').textContent    = perf.period;
    document.getElementById('modal-evaluator').textContent = perf.evaluator;
    document.getElementById('modal-dueDate').textContent   = perf.dueDate;
    document.getElementById('modal-rating').textContent    = perf.rating ? perf.rating + ' / 5.0' : 'Not yet rated';
    document.getElementById('modal-action-btn').textContent = perf.status === 'Completed' ? 'View Full Report' : 'Start Evaluation';

    document.getElementById('view-modal').style.display = 'flex';
}

/* ── Evaluate modal ── */
function showEvaluateModal(perfId) {
    const perf = performanceData.find(p => p.id === perfId);
    if (!perf) return;
    currentEvalId = perfId;
    document.getElementById('eval-name').textContent     = perf.name;
    document.getElementById('eval-position').textContent = perf.position;
    document.getElementById('eval-rating').value         = '4.0';
    document.getElementById('eval-comments').value       = '';
    document.getElementById('evaluate-modal').style.display = 'flex';
}

function submitEvaluation() {
    const rating = parseFloat(document.getElementById('eval-rating').value);
    if (isNaN(rating) || rating < 1 || rating > 5) {
        alert('Please enter a rating between 1.0 and 5.0');
        return;
    }
    document.querySelectorAll('#performance-table-body tr').forEach(row => {
        const perf = performanceData.find(p => p.name === row.querySelector('.emp-name')?.textContent);
        if (!perf || perf.id !== currentEvalId) return;

        perf.rating = rating;
        perf.status = 'Completed';
        row.dataset.status = 'Completed';

        row.querySelector('td:nth-child(7) .badge-status').textContent = 'Completed';
        row.querySelector('td:nth-child(7) .badge-status').className   = 'badge-status processed';

        let stars = '<div class="star-wrap"><div class="stars">';
        for (let i = 1; i <= 5; i++) {
            const c = i <= Math.round(rating) ? '#6b3fa0' : '#e4e3f0';
            stars += `<svg width="14" height="14" viewBox="0 0 24 24" fill="${c}" stroke="${c}" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;
        }
        stars += `</div><span style="font-size:13px;color:#0b044d;font-weight:600">${rating}</span></div>`;
        row.querySelector('td:nth-child(6)').innerHTML = stars;

        row.querySelector('td:last-child .row-actions').innerHTML =
            `<button class="btn-view" onclick="viewPerformance('${perf.id}')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>`;
    });
    closeModal('evaluate-modal');
    filterPerformance(document.getElementById('banner-search').value);
}

function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal('view-modal'); closeModal('evaluate-modal'); }
});
</script>
@endpush
