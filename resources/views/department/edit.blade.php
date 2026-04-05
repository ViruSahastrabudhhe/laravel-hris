@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('departments.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Departments
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Edit Department</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Update department information</p>
    </div>

    <form action="{{ route('departments.update', $department) }}" method="post" class="auth-form">
        @csrf
        @method('put')

        <div class="auth-field">
            <label>Department Name <span style="color:#dc2626">*</span></label>
            <input type="text" name="name" value="{{ old('name', $department->name) }}" placeholder="e.g. Human Resources" required>
        </div>

        <div class="auth-field">
            <label>Description</label>
            <textarea name="description" rows="4" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Brief description of the department">{{ old('description', $department->description) }}</textarea>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('departments.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">
                Cancel
            </a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update Department
            </button>
        </div>
    </form>
</div>

@endsection
