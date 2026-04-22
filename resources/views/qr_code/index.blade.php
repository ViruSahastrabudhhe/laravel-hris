@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <h2 style="color:#0b044d;font-size:24px;font-weight:700;margin:0">Generate QR Code</h2>
    <p style="color:#9999bb;font-size:14px;margin:4px 0 0">Create attendance QR codes for employees</p>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">QR Code Generator</p>
            <p class="table-sub">Select employee and enter attendance details</p>
        </div>
    </div>

    <div class="table-wrapper" style="padding:30px">
        <form action="{{ route('qr-code.generate') }}" method="POST" style="max-width:600px">
            @csrf

            <div style="margin-bottom:20px">
                <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Employee *</label>
                <select name="employee_id" required style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }} - {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->position->title }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Date *</label>
                <input type="date" name="date" required value="{{ date('Y-m-d') }}" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Time In</label>
                    <input type="time" name="time_in" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Time Out</label>
                    <input type="time" name="time_out" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">PM In</label>
                    <input type="time" name="pm_in" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">PM Out</label>
                    <input type="time" name="pm_out" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Overtime In</label>
                    <input type="time" name="overtime_in" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#0b044d;margin-bottom:8px">Overtime Out</label>
                    <input type="time" name="overtime_out" style="width:100%;padding:10px 14px;border:1.5px solid #dddcf0;border-radius:8px;font-size:14px;color:#0b044d">
                </div>
            </div>

            <button type="submit" style="padding:12px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#0b044d,#1a0f6e);color:#fff;font-size:14px;font-weight:700;cursor:pointer">
                Generate QR Code
            </button>
        </form>
    </div>
</div>
@endsection
