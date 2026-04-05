@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('attendances.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Attendance
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Import Attendance</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Upload a CSV file to bulk import attendance records</p>
    </div>

    <form action="{{ route('attendances.csvStore') }}" method="post" enctype="multipart/form-data" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>CSV File <span style="color:#dc2626">*</span></label>
            <input type="file" name="csv_file" id="file" accept=".csv"
                style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13px;color:#1a1a3a;background:#fafafe;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif">
        </div>

        <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;font-size:12.5px;color:#6b6a8a;line-height:1.7">
            <strong style="color:#0b044d;display:block;margin-bottom:4px">CSV Format Requirements</strong>
            The CSV file should include columns for: employee ID, date, time in, time out, break start, break end, overtime in, overtime out.
        </div>

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('attendances.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Import CSV
            </button>
        </div>
    </form>
</div>

@endsection
