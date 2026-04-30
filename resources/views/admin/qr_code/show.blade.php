@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <a href="{{ url()->previous() }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to QR Codes
    </a>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">QR Code Generated</p>
            <p class="table-sub">{{ $employee->first_name }} {{ $employee->last_name }} — Valid until {{ $qrScan->expires_at->format('M d, Y') }}</p>
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
            <div id="qrcode-print" style="display:none"></div>

            <div style="text-align:left;background:#fff;border-radius:12px;padding:20px">
                <h3 style="color:#0b044d;font-size:18px;font-weight:700;margin:0 0 16px">Employee Details</h3>
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
                        <span style="color:#9999bb;font-size:13px;font-weight:600">Valid Until</span>
                        <strong style="color:#0b044d;font-size:13px">{{ $qrScan->expires_at->format('F d, Y') }}</strong>
                    </div>
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

var qrPrint = new QRCode(document.getElementById("qrcode-print"), {
    text: @json($qrData),
    width: 1024,
    height: 1024,
    colorDark: "#0b044d",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

function printQR() {
    setTimeout(function() {
        var img = document.querySelector('#qrcode-print img') || document.querySelector('#qrcode-print canvas');
        var src = img.tagName === 'CANVAS' ? img.toDataURL() : img.src;
        var win = window.open('', '_blank');
        win.document.write('<html><head><title>QR Code - {{ $employee->first_name }} {{ $employee->last_name }}</title><style>*{margin:0;padding:0;box-sizing:border-box}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;background:#fff}img{width:90vmin;height:90vmin;image-rendering:pixelated}p{margin-top:16px;font-size:18px;font-weight:700;color:#0b044d}small{color:#666;font-size:13px}@media print{@page{margin:0}}</style></head><body><img src="'+src+'"><p>{{ $employee->first_name }} {{ $employee->last_name }}</p><small>EMP-{{ str_pad($employee->id, 3, "0", STR_PAD_LEFT) }} &mdash; Valid until {{ $qrScan->expires_at->format("M d, Y") }}</small></body></html>');
        win.document.close();
        win.focus();
        win.onload = function() { win.print(); };
    }, 300);
}
</script>
@endpush


@endsection