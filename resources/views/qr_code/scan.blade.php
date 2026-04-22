@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <h2 style="color:#0b044d;font-size:24px;font-weight:700;margin:0">Scan QR Code</h2>
    <p style="color:#9999bb;font-size:14px;margin:4px 0 0">Scan attendance QR codes using camera or upload</p>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">QR Code Scanner</p>
            <p class="table-sub">Use camera or upload QR code image</p>
        </div>
    </div>

    <div class="table-wrapper" style="padding:30px">
        <div style="max-width:800px;margin:0 auto">
            
            <!-- Tab Navigation -->
            <div style="display:flex;gap:10px;margin-bottom:24px;border-bottom:2px solid #f0effe">
                <button onclick="switchTab('camera')" id="camera-tab" class="scanner-tab active">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Camera Scan
                </button>
                <button onclick="switchTab('upload')" id="upload-tab" class="scanner-tab">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Image
                </button>
            </div>

            <!-- Camera Scanner -->
            <div id="camera-scanner" class="scanner-content">
                <div style="background:#f7f6ff;border-radius:12px;padding:20px;text-align:center">
                    <div id="reader" style="width:100%;max-width:500px;margin:0 auto"></div>
                    <p style="color:#9999bb;font-size:13px;margin-top:16px">Position the QR code within the frame</p>
                </div>
            </div>

            <!-- Upload Scanner -->
            <div id="upload-scanner" class="scanner-content" style="display:none">
                <div style="background:#f7f6ff;border-radius:12px;padding:40px;text-align:center">
                    <form id="upload-form" enctype="multipart/form-data">
                        @csrf
                        <div style="border:2px dashed #dddcf0;border-radius:12px;padding:40px;background:#fff">
                            <svg width="48" height="48" fill="none" stroke="#9999bb" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 16px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <p style="color:#0b044d;font-size:16px;font-weight:600;margin:0 0 8px">Upload QR Code Image</p>
                            <p style="color:#9999bb;font-size:13px;margin:0 0 20px">PNG, JPG up to 5MB</p>
                            <input type="file" id="qr-file" name="qr_image" accept="image/*" style="display:none" onchange="handleFileUpload(this)">
                            <label for="qr-file" style="display:inline-block;padding:10px 20px;border-radius:8px;background:linear-gradient(135deg,#0b044d,#1a0f6e);color:#fff;font-size:14px;font-weight:600;cursor:pointer">
                                Choose File
                            </label>
                        </div>
                        <div id="preview-container" style="margin-top:20px;display:none">
                            <img id="preview-image" style="max-width:300px;border-radius:8px;margin-bottom:16px">
                            <br>
                            <button type="button" onclick="processUploadedQR()" style="padding:10px 20px;border-radius:8px;border:none;background:#15803d;color:#fff;font-size:14px;font-weight:600;cursor:pointer">
                                Process QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result Display -->
            <div id="scan-result" style="margin-top:24px;display:none">
                <div style="background:#f0fdf4;border:2px solid #22c55e;border-radius:12px;padding:20px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                        <svg width="24" height="24" fill="none" stroke="#15803d" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <h3 style="color:#15803d;font-size:18px;font-weight:700;margin:0">Scan Successful</h3>
                    </div>
                    <div id="result-details"></div>
                </div>
            </div>

            <div id="scan-error" style="margin-top:24px;display:none">
                <div style="background:#fef2f2;border:2px solid #ef4444;border-radius:12px;padding:20px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                        <svg width="24" height="24" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <h3 style="color:#dc2626;font-size:18px;font-weight:700;margin:0">Scan Failed</h3>
                    </div>
                    <p id="error-message" style="color:#991b1b;font-size:14px;margin:0"></p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode;

function switchTab(tab) {
    document.querySelectorAll('.scanner-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.scanner-content').forEach(c => c.style.display = 'none');
    
    if (tab === 'camera') {
        document.getElementById('camera-tab').classList.add('active');
        document.getElementById('camera-scanner').style.display = 'block';
        startCamera();
    } else {
        document.getElementById('upload-tab').classList.add('active');
        document.getElementById('upload-scanner').style.display = 'block';
        stopCamera();
    }
}

function startCamera() {
    if (html5QrCode) {
        html5QrCode.clear();
    }
    
    html5QrCode = new Html5Qrcode("reader");
    
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        onScanError
    ).catch(err => {
        console.error("Camera error:", err);
    });
}

function stopCamera() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
        }).catch(err => console.error("Stop error:", err));
    }
}

function onScanSuccess(decodedText, decodedResult) {
    stopCamera();
    processScan(decodedText);
}

function onScanError(errorMessage) {
    // Ignore scan errors (they happen continuously while scanning)
}

function handleFileUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function processUploadedQR() {
    const fileInput = document.getElementById('qr-file');
    if (!fileInput.files[0]) return;

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.scanFile(fileInput.files[0], true)
        .then(decodedText => {
            processScan(decodedText);
        })
        .catch(err => {
            showError('Failed to decode QR code from image');
        });
}

function processScan(qrData) {
    fetch('/api/qr-scanner/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ qr_data: qrData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        showError('Network error: ' + error.message);
    });
}

function showSuccess(data) {
    document.getElementById('scan-error').style.display = 'none';
    document.getElementById('scan-result').style.display = 'block';
    
    const details = `
        <div style="display:grid;gap:8px">
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #bbf7d0">
                <span style="color:#15803d;font-size:13px;font-weight:600">Employee</span>
                <strong style="color:#15803d;font-size:13px">${data.data.employee_name}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #bbf7d0">
                <span style="color:#15803d;font-size:13px;font-weight:600">Date</span>
                <strong style="color:#15803d;font-size:13px">${data.data.date}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #bbf7d0">
                <span style="color:#15803d;font-size:13px;font-weight:600">Time In</span>
                <strong style="color:#15803d;font-size:13px">${data.data.time_in || 'N/A'}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0">
                <span style="color:#15803d;font-size:13px;font-weight:600">Scanned At</span>
                <strong style="color:#15803d;font-size:13px">${new Date(data.data.scanned_at).toLocaleString()}</strong>
            </div>
        </div>
    `;
    
    document.getElementById('result-details').innerHTML = details;
    
    setTimeout(() => {
        location.reload();
    }, 3000);
}

function showError(message) {
    document.getElementById('scan-result').style.display = 'none';
    document.getElementById('scan-error').style.display = 'block';
    document.getElementById('error-message').textContent = message;
}

// Start camera on page load
startCamera();
</script>
@endpush

@push('styles')
<style>
.scanner-tab {
    padding: 12px 20px;
    border: none;
    background: transparent;
    color: #9999bb;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.scanner-tab.active {
    color: #0b044d;
    border-bottom-color: #0b044d;
}

.scanner-tab:hover {
    color: #0b044d;
}
</style>
@endpush
@endsection
