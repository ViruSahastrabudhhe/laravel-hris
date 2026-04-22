@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <a href="{{ route('qr-code.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Generator
    </a>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">QR Code Generated</p>
            <p class="table-sub">{{ $employee->first_name }} {{ $employee->last_name }} - {{ $qrScan->date }}</p>
        </div>
        <div class="table-actions">
            <button onclick="printQR()" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                Print QR Code
            </button>
        </div>
    </div>

    <div class="table-wrapper" style="padding:40px;text-align:center" id="qr-printable">
        <div style="background:#f7f6ff;border-radius:16px;padding:40px;display:inline-block">
            <div style="background:#fff;padding:20px;border-radius:12px;margin-bottom:20px">
                <div id="qrcode"></div>
            </div>
            
            <div style="text-align:left;background:#fff;border-radius:12px;padding:20px">
                <h3 style="color:#0b044d;font-size:18px;font-weight:700;margin:0 0 16px">Attendance Details</h3>
                
                <div style="display:grid;gap:10px">
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Employee</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Employee ID</span>
                        <strong style="color:#0b044d;font-size:13px">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Date</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->date }}</strong>
                    </div>
                    @if($qrScan->time_in)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Time In</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->time_in }}</strong>
                    </div>
                    @endif
                    @if($qrScan->time_out)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Time Out</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->time_out }}</strong>
                    </div>
                    @endif
                    @if($qrScan->pm_in)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">PM In</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->pm_in }}</strong>
                    </div>
                    @endif
                    @if($qrScan->pm_out)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">PM Out</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->pm_out }}</strong>
                    </div>
                    @endif
                    @if($qrScan->overtime_in)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Overtime In</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->overtime_in }}</strong>
                    </div>
                    @endif
                    @if($qrScan->overtime_out)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0effe">
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Overtime Out</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->overtime_out }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
var qrcode = new QRCode(document.getElementById("qrcode"), {
    text: @json($qrData),
    width: 256,
    height: 256,
    colorDark: "#0b044d",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

function printQR() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #qr-printable, #qr-printable * {
        visibility: visible;
    }
    #qr-printable {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
@endpush
@endsection
