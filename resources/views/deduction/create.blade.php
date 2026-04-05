@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('deductions.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Deductions
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add New Deduction</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Create a new mandatory or optional deduction</p>
    </div>

    <form action="{{ route('deductions.store') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>Deduction Name <span style="color:#dc2626">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. PhilHealth" required>
        </div>

        <div class="auth-field">
            <label>Rate <span style="font-size:11px;color:#9999bb">(leave blank if fixed amount)</span></label>
            <input type="number" name="rate" value="{{ old('rate') }}" placeholder="e.g. 0.03 for 3%" step="0.0001" min="0" max="1">
        </div>

        <div class="auth-field">
            <label>Type <span style="color:#dc2626">*</span></label>
            <select name="type" required>
                <option value="">Select deduction type</option>
                @foreach($deductionType as $deduction)
                <option value="{{ $deduction->value }}" {{ old('type') == $deduction->value ? 'selected' : '' }}>{{ $deduction->value }}</option>
                @endforeach
            </select>
        </div>

        <div class="auth-field">
            <label>Description</label>
            <textarea name="description" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Brief description of this deduction">{{ old('description') }}</textarea>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('deductions.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Deduction
            </button>
        </div>
    </form>
</div>

@endsection
