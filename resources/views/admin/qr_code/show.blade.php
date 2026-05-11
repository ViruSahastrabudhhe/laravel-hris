@extends('layouts.admin')
@php $hideChat = true; @endphp

@push('styles')
    @vite('resources/css/admin/adminAttendance.css')
@endpush

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
        </div>
        <div>
            <h2>QR Code Generated</h2>
            <p>{{ $employee->first_name }} {{ $employee->last_name }} &nbsp;·&nbsp; Valid until {{ $qrScan->expires_at->format('F d, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <button onclick="printQR()" class="modal-btn-primary">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Print QR Code
        </button>
    </div>
</div>

<div style="display:flex;justify-content:center;padding:20px 0">
    <div style="background:#fff;border:1.5px solid #e4e3f0;border-radius:18px;box-shadow:0 4px 24px rgba(11,4,77,0.07);padding:40px;width:100%;max-width:420px;text-align:center">

        {{-- Avatar + name --}}
        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;margin-bottom:28px">
            <div style="width:56px;height:56px;border-radius:14px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:700">
                {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
            </div>
            <div>
                <p style="font-size:16px;font-weight:800;color:#0b044d;margin:0">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                <p style="font-size:12px;color:#9999bb;margin:2px 0 0">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        {{-- QR Code --}}
        <div style="background:#f7f6ff;border-radius:14px;padding:24px;display:inline-block;margin-bottom:24px">
            <div style="background:#fff;padding:16px;border-radius:10px;box-shadow:0 2px 8px rgba(11,4,77,0.08)">
                <div id="qrcode"></div>
            </div>
        </div>
        <div id="qrcode-print" style="display:none"></div>

        {{-- Details --}}
        <div style="text-align:left;background:#f7f6ff;border-radius:12px;padding:16px 20px">
            <div class="modal-row"><span>Position</span><strong>{{ $employee->position->title ?? '—' }}</strong></div>
            <div class="modal-row"><span>Department</span><strong>{{ $employee->department->name ?? '—' }}</strong></div>
            <div class="modal-row" style="border-bottom:none"><span>Valid Until</span><strong style="color:#15803d">{{ $qrScan->expires_at->format('F d, Y') }}</strong></div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('qrcode'), {
    text: @json($qrData),
    width: 220, height: 220,
    colorDark: '#0b044d', colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
});

new QRCode(document.getElementById('qrcode-print'), {
    text: @json($qrData),
    width: 1024, height: 1024,
    colorDark: '#0b044d', colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
});

function printQR() {
    setTimeout(function () {
        var img = document.querySelector('#qrcode-print img') || document.querySelector('#qrcode-print canvas');
        var src = img.tagName === 'CANVAS' ? img.toDataURL() : img.src;
        var win = window.open('', '_blank');
        win.document.write('<html><head><title>QR Code - {{ $employee->first_name }} {{ $employee->last_name }}</title><style>*{margin:0;padding:0;box-sizing:border-box}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;background:#fff}img{width:90vmin;height:90vmin;image-rendering:pixelated}p{margin-top:16px;font-size:18px;font-weight:700;color:#0b044d}small{color:#666;font-size:13px}@media print{@page{margin:0}}</style></head><body><img src="' + src + '"><p>{{ $employee->first_name }} {{ $employee->last_name }}</p><small>EMP-{{ str_pad($employee->id, 3, "0", STR_PAD_LEFT) }} &mdash; Valid until {{ $qrScan->expires_at->format("M d, Y") }}</small></body></html>');
        win.document.close();
        win.focus();
        win.onload = function () { win.print(); };
    }, 300);
}
</script>
@endpush
