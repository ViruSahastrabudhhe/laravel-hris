@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <a href="{{ url()->previous() }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to QR Codes
    </a>
</div>

<div class="auth-card auth-card-wide" style="max-width:800px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Generate QR Code</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Generate a monthly attendance QR code for an employee</p>
    </div>

    <form action="{{ route('qr-code.generate') }}" method="POST" class="auth-form">
        @csrf
        <div class="auth-field">
            <label>Employee <span style="color:#dc2626">*</span></label>
            <select name="employee_id" required style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                <option value="">Select Employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ isset($employee) && $employee->id === $emp->id ? 'selected' : '' }}>
                        EMP-{{ str_pad($emp->id, 3, '0', STR_PAD_LEFT) }} - {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->position->title }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="padding:12px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#0b044d,#1a0f6e);color:#fff;font-size:14px;font-weight:700;cursor:pointer">
            Generate QR Code
        </button>
    </form>
</div>
@endsection