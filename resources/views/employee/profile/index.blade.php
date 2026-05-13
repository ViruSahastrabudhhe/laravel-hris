@extends('layouts.employee')

@php
    use App\Enums\EmployeeTrainingStatus;
    use Carbon\Carbon;

    $yearsOfService = 0;
    $performanceRating = 0;
    $leaveBalance = $employee->EmployeeLeaveBalance->where('type', 'Sick')->first()->amount;
    $trainingsCompleted = 0;

    foreach ($employee->employeeTraining as $et) {
        if ($et->status === EmployeeTrainingStatus::Completed->value) {
            $trainingsCompleted++;
        }
    }

    $employeeHireDate = Carbon::parse($employee->created_at)->format(config('app.day_month'));
    $employeeBirthDate = Carbon::parse($employee->date_of_birth)->format(config('app.day_month'));
    $employeeFirstNameFirstLetter = strtoupper(substr($employee->first_name, 0, 1));
    $employeeLastNameFirstLetter = strtoupper(substr($employee->last_name, 0, 1));
    $employeeAvatar = $employeeFirstNameFirstLetter . $employeeLastNameFirstLetter;
    $employeeAddress = $employee->address->address . ', ' . $employee->address->city . ', ' . $employee->address->province . ', ' . $employee->address->country . ', ' . $employee->address->zip_code;
@endphp

@section('page-content')
<div class="profile-header">
    <div class="profile-header-left">
        <div class="profile-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">{{ $employeeAvatar }}</div>
        <div class="profile-info">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                <h2>{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                <span class="banner-badge"><span class="banner-badge-dot"></span>Active</span>
            </div>
            <p>{{ $employee->position->title }} · {{ $employee->department->name }}</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span class="banner-badge outline">{{ $employee->employment_type }}</span>
                <span class="banner-badge outline">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>
    <button class="btn-edit-profile" onclick="openProfileEditModal(this)"
        data-employee_id="{{ $employee->id }}"
    >
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit Profile
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Years of Service</p>
            <div class="stat-icon-wrap" style="background:#0b044d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $yearsOfService }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Since {{ $employeeHireDate }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Performance Rating</p>
            <div class="stat-icon-wrap" style="background:#15803d15"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $performanceRating }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Latest evaluation</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Leave Balance</p>
            <div class="stat-icon-wrap" style="background:#d9bb0015"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d9bb00" stroke-width="2"><path d="M12 2v6l3 3"/><circle cx="12" cy="12" r="10"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $leaveBalance }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Days remaining</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Trainings Completed</p>
            <div class="stat-icon-wrap" style="background:#8e1e1815"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
        </div>
        <h2 class="stat-value">{{ $trainingsCompleted }}</h2>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Total programs</p>
        </div>
    </div>
</div>

<section class="table-section">
    <div class="table-header">
        <div>
            <h3 class="table-title">Profile Information</h3>
            <p class="table-sub">View and manage your personal details</p>
        </div>
    </div>

    <div class="pmodal-tabs">
        <button class="pmodal-tab active" onclick="switchTab('personal', this)">Personal Info</button>
        <button class="pmodal-tab" onclick="switchTab('employment', this)">Employment</button>
        <button class="pmodal-tab" onclick="switchTab('government', this)">Government IDs</button>
        <button class="pmodal-tab" onclick="switchTab('emergency', this)">Emergency Contact</button>
    </div>

    <div style="padding:28px 32px;">
        <div id="tab-personal" class="tab-content">
            <div class="pmodal-grid">
                <div class="pmodal-field"><span>Full Name</span><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></div>
                <div class="pmodal-field"><span>Gender</span><strong>{{ $employee->gender }}</strong></div>
                <div class="pmodal-field"><span>Date of Birth</span><strong>{{ $employeeBirthDate }}</strong></div>
                <div class="pmodal-field"><span>Contact No.</span><strong>{{ $employee->phone_number }}</strong></div>
                <div class="pmodal-field pmodal-full"><span>Email Address</span><strong>{{ $employee->email }}</strong></div>
                <div class="pmodal-field pmodal-full"><span>Address</span><strong>{{ $employeeAddress }}</strong></div>
            </div>
        </div>
        <div id="tab-employment" class="tab-content hidden">
            <div class="pmodal-grid">
                <div class="pmodal-field"><span>Employee ID</span><strong>EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</strong></div>
                <div class="pmodal-field"><span>Employment Type</span><strong>{{ $employee->employment_type }}</strong></div>
                <div class="pmodal-field"><span>Date Hired</span><strong>{{ $employeeHireDate }}</strong></div>
                <div class="pmodal-field"><span>Status</span><strong>{{ $employee->is_active ? 'Going Strong' : 'Not Yet' }}</strong></div>
                <div class="pmodal-field pmodal-full"><span>Position / Designation</span><strong>{{ $employee->position->title }}</strong></div>
                <div class="pmodal-field pmodal-full"><span>Department / Office</span><strong>{{ $employee->department->name }}</strong></div>
            </div>
        </div>
        <div id="tab-government" class="tab-content hidden">
            <div class="pmodal-grid">
                <div class="pmodal-field"><span>GSIS No.</span><strong>3456789012</strong></div>
                <div class="pmodal-field"><span>PhilHealth No.</span><strong>34-567890123-4</strong></div>
                <div class="pmodal-field"><span>Pag-IBIG No.</span><strong>3456-7890-1234</strong></div>
                <div class="pmodal-field"><span>TIN</span><strong>345-678-901</strong></div>
            </div>
        </div>
        <div id="tab-emergency" class="tab-content hidden">
            <div class="pmodal-grid">
                <div class="pmodal-field"><span>Contact Person</span><strong>Roberto Reyes</strong></div>
                <div class="pmodal-field"><span>Relationship</span><strong>Spouse</strong></div>
                <div class="pmodal-field"><span>Phone Number</span><strong>09171234567</strong></div>
            </div>
        </div>
    </div>
</section>

{{-- Edit Profile Modal --}}
<div class="modal-overlay" id="profile-edit-modal" style="display:none;" onclick="if(event.target===this)closeModal('profile-edit-modal')">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EDIT PROFILE · <span id="modal-page-label">Page 1 of 2</span></span>
                <h3 class="modal-title" id="modal-page-title">Personal Information</h3>
            </div>
            <button class="modal-close" onclick="closeModal('profile-edit-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="profile-edit-form" method="POST">
            @csrf
            @method('PUT')

            {{-- Page 1: Personal --}}
            <div class="modal-body modal-page" id="edit-page-1">
                <div class="form-grid">
                    <div class="form-field">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="{{ $employee->first_name }}" required>
                    </div>
                    <div class="form-field">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="{{ $employee->last_name }}" required>
                    </div>
                    <div class="form-field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select gender</option>
                            @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                            <option value="{{ $g }}" {{ old('gender', $employee->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-field">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" value="{{ $employee->phone_number }}" required>
                    </div>
                    <div class="form-field form-full">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ $employee->email }}" required>
                    </div>
                </div>
            </div>

            {{-- Page 2: Address --}}
            <div class="modal-body modal-page" id="edit-page-2" style="display:none;">
                <div class="form-grid">
                    <div class="form-field form-full">
                        <label>Street Address</label>
                        <input type="text" name="address[address]" value="{{ $employee->address->address }}" required>
                    </div>
                    <div class="form-field">
                        <label>City</label>
                        <input type="text" name="address[city]" value="{{ $employee->address->city }}" required>
                    </div>
                    <div class="form-field">
                        <label>Province</label>
                        <input type="text" name="address[province]" value="{{ $employee->address->province }}" required>
                    </div>
                    <div class="form-field">
                        <label>Country</label>
                        <input type="text" name="address[country]" value="{{ $employee->address->country }}" required>
                    </div>
                    <div class="form-field">
                        <label>Zip Code</label>
                        <input type="text" name="address[zip_code]" value="{{ $employee->address->zip_code }}" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" id="btn-prev" onclick="editModalPrev()" style="display:none;">Back</button>
                <button type="button" class="modal-btn-ghost" id="btn-cancel" onclick="closeModal('profile-edit-modal')">Cancel</button>
                <button type="button" class="modal-btn-primary" id="btn-next" onclick="editModalNext()">
                    Next
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                <button type="submit" class="modal-btn-primary" id="btn-save" style="display:none;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@push('scripts')
<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.querySelectorAll('.pmodal-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    const editPageTitles = ['Personal Information', 'Address'];
    let editCurrentPage = 1;
    const editTotalPages = 2;

    function openProfileEditModal(button) {
        const data = button.dataset;
        editCurrentPage = 1;

        const employeeId = data.employee_id;

        var url = "{{ route('my_profile.update', ':id') }}".replace(':id', employeeId);
        document.getElementById('profile-edit-form').action = url;

        updateEditModal();
        document.getElementById('profile-edit-modal').style.display = 'flex';
    }

    function updateEditModal() {
        for (let i = 1; i <= editTotalPages; i++) {
            document.getElementById('edit-page-' + i).style.display = i === editCurrentPage ? 'block' : 'none';
        }

        document.getElementById('modal-page-label').textContent = `Page ${editCurrentPage} of ${editTotalPages}`;
        document.getElementById('modal-page-title').textContent = editPageTitles[editCurrentPage - 1];
        document.getElementById('btn-prev').style.display = editCurrentPage > 1 ? 'inline-flex' : 'none';
        document.getElementById('btn-cancel').style.display = editCurrentPage === 1 ? 'inline-flex' : 'none';
        document.getElementById('btn-next').style.display = editCurrentPage < editTotalPages ? 'inline-flex' : 'none';
        document.getElementById('btn-save').style.display = editCurrentPage === editTotalPages ? 'inline-flex' : 'none';
    }

    function editModalNext() {
        if (editCurrentPage < editTotalPages) { editCurrentPage++; updateEditModal(); }
    }

    function editModalPrev() {
        if (editCurrentPage > 1) { editCurrentPage--; updateEditModal(); }
    }
</script>
@endpush

@endpush
