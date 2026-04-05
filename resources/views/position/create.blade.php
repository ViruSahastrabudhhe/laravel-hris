@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('positions.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Positions
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add New Position</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Create a new job position and salary grade</p>
    </div>

    <form action="{{ route('positions.store') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>Position Title <span style="color:#dc2626">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Administrative Officer" required>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Salary Grade <span style="color:#dc2626">*</span></label>
                <input type="text" name="salary_grade" value="{{ old('salary_grade') }}" placeholder="e.g. 11" required>
            </div>
            <div class="auth-field">
                <label>Step <span style="color:#dc2626">*</span></label>
                <input type="number" name="step" value="{{ old('step', 1) }}" placeholder="e.g. 1" min="1" required>
            </div>
        </div>

        <div class="auth-field">
            <label>Monthly Salary <span style="color:#dc2626">*</span></label>
            <input type="number" name="salary_amount" value="{{ old('salary_amount') }}" placeholder="e.g. 25000" step="0.01" required>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('positions.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Position
            </button>
        </div>
    </form>
</div>

@endsection
