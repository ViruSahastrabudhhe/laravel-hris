<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Attendance Scanner</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0effe; min-height: 100vh; display: flex; flex-direction: column; }

        .page-header {
            background: linear-gradient(135deg, #0b044d, #1a0f6e);
            color: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-header h1 { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .page-header a { color: rgba(255,255,255,0.7); font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .page-header a:hover { color: #fff; }

        .main { display: flex; flex: 1; gap: 0; height: calc(100vh - 57px); }

        /* LEFT — Camera */
        .panel-camera {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: #fff;
            border-right: 1px solid #e4e3f0;
        }
        .panel-camera h2 { font-size: 16px; font-weight: 700; color: #0b044d; margin-bottom: 6px; }
        .panel-camera p { font-size: 13px; color: #9999bb; margin-bottom: 24px; }

        #reader-wrap {
            width: 100%;
            max-width: 460px;
            background: #f7f6ff;
            border-radius: 16px;
            padding: 16px;
            overflow: hidden;
        }
        #reader { width: 100%; }

        #status-bar {
            margin-top: 20px;
            text-align: center;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #status-text { font-size: 13px; color: #9999bb; }

        .result-card {
            display: none;
            margin-top: 20px;
            width: 100%;
            max-width: 460px;
            border-radius: 12px;
            padding: 16px 20px;
        }
        .result-card.success { background: #f0fdf4; border: 1.5px solid #22c55e; }
        .result-card.error   { background: #fef2f2; border: 1.5px solid #ef4444; }
        .result-card .rc-title { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; margin-bottom: 10px; }
        .result-card.success .rc-title { color: #15803d; }
        .result-card.error   .rc-title { color: #dc2626; }
        .result-card .rc-row { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .result-card .rc-row:last-child { border-bottom: none; }
        .result-card.success .rc-row { color: #15803d; }
        .result-card.error   .rc-row { color: #991b1b; }

        /* RIGHT — History */
        .panel-history {
            width: 380px;
            min-width: 320px;
            display: flex;
            flex-direction: column;
            background: #fff;
            overflow: hidden;
        }
        .history-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0effe;
        }
        .history-header h2 { font-size: 15px; font-weight: 700; color: #0b044d; }
        .history-header p  { font-size: 12px; color: #9999bb; margin-top: 2px; }

        .history-list { flex: 1; overflow-y: auto; padding: 12px 16px; display: flex; flex-direction: column; gap: 8px; }

        .history-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #fafafe;
            border: 1px solid #f0effe;
            transition: background 0.15s;
        }
        .history-item.new { animation: fadeIn 0.4s ease; border-color: #bbf7d0; background: #f0fdf4; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        .hi-avatar {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .hi-info { flex: 1; min-width: 0; }
        .hi-name  { font-size: 13px; font-weight: 600; color: #0b044d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .hi-meta  { font-size: 11px; color: #9999bb; margin-top: 2px; }
        .hi-badge { font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 5px; flex-shrink: 0; }
        .hi-badge.scanned { background: #f0fdf4; color: #15803d; }
        .hi-badge.pending { background: #fefce8; color: #a16207; }

        .history-empty { text-align: center; padding: 40px 20px; color: #9999bb; font-size: 13px; }

        @media (max-width: 768px) {
            .main { flex-direction: column; height: auto; }
            .panel-history { width: 100%; min-width: unset; border-top: 1px solid #e4e3f0; max-height: 50vh; }
        }
    </style>
</head>
<body>

<div class="page-header">
    <h1>
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
        QR Attendance Scanner
    </h1>
    <a href="{{ url()->previous() }}">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Attendance
    </a>
</div>

<div class="main">

    {{-- LEFT: Camera --}}
    <div class="panel-camera">
        <h2>Scan QR Code</h2>
        <p>Position the employee QR code within the camera frame</p>

        <div id="reader-wrap">
            <div id="reader"></div>
        </div>

        <div id="status-bar">
            <span id="status-text">Starting camera…</span>
        </div>

        <div class="result-card success" id="success-card">
            <div class="rc-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Attendance Recorded
            </div>
            <div id="success-details"></div>
        </div>

        <div class="result-card error" id="error-card">
            <div class="rc-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                Scan Failed
            </div>
            <p id="error-text" style="font-size:13px;color:#991b1b"></p>
        </div>
    </div>

    {{-- RIGHT: History --}}
    <div class="panel-history">
        <div class="history-header">
            <h2>Recent Scans</h2>
            <p>Today's attendance activity</p>
        </div>
        <div class="history-list" id="history-list">
            <div class="history-empty" id="history-empty">No scans yet today</div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const avatarColors = ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'];
let scanner, scanning = true;

function initials(name) {
    const p = name.trim().split(' ');
    return (p[0][0] + (p[p.length-1][0] || '')).toUpperCase();
}

function setStatus(msg, color = '#9999bb') {
    document.getElementById('status-text').innerHTML = `<span style="color:${color}">${msg}</span>`;
}

function onScanSuccess(decodedText) {
    if (!scanning) return;
    scanning = false;
    scanner.pause(true);
    setStatus('Processing…', '#a16207');

    fetch('/api/qr-scanner/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ qr_data: decodedText })
    })
    .then(r => {
        if (!r.ok && r.headers.get('content-type')?.includes('text/html')) {
            throw new Error('Server error (HTTP ' + r.status + ')');
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            showSuccess(data);
            prependHistory(data);
        } else {
            showError(data.message || 'Scan failed');
            setTimeout(() => {
                hideCards();
                setStatus('Position QR code within the frame');
                scanning = true;
                scanner.resume();
            }, 3000);
        }
    })
    .catch(err => {
        showError('Network error: ' + err.message);
        setTimeout(() => {
            hideCards();
            setStatus('Position QR code within the frame');
            scanning = true;
            scanner.resume();
        }, 3000);
    });
}

function showSuccess(d) {
    hideCards();
    setStatus('✓ Recorded', '#15803d');
    const card = document.getElementById('success-card');
    document.getElementById('success-details').innerHTML = `
        <div class="rc-row"><span>Employee</span><strong>${d.employee_name}</strong></div>
        <div class="rc-row"><span>Date</span><strong>${d.date}</strong></div>
        <div class="rc-row"><span>Time In</span><strong>${d.time_in || 'N/A'}</strong></div>
        <div class="rc-row"><span>Time Out</span><strong>${d.time_out || 'N/A'}</strong></div>
    `;
    card.style.display = 'block';
    setTimeout(() => {
        hideCards();
        setStatus('Position QR code within the frame');
        scanning = true;
        scanner.resume();
    }, 4000);
}

function showError(msg) {
    hideCards();
    document.getElementById('error-text').textContent = msg;
    document.getElementById('error-card').style.display = 'block';
}

function hideCards() {
    document.getElementById('success-card').style.display = 'none';
    document.getElementById('error-card').style.display = 'none';
}

function prependHistory(d) {
    const empty = document.getElementById('history-empty');
    if (empty) empty.remove();

    const list = document.getElementById('history-list');
    const color = avatarColors[d.employee_id % 5];
    const div = document.createElement('div');
    div.className = 'history-item new';
    div.innerHTML = `
        <div class="hi-avatar" style="background:${color}">${initials(d.employee_name)}</div>
        <div class="hi-info">
            <div class="hi-name">${d.employee_name}</div>
            <div class="hi-meta">${d.date}${d.time_in ? ' · In: ' + d.time_in : ''} · just now</div>
        </div>
        <span class="hi-badge scanned">Scanned</span>
    `;
    list.prepend(div);
}

scanner = new Html5Qrcode('reader');
scanner.start(
    { facingMode: 'environment' },
    { fps: 10, qrbox: { width: 260, height: 260 } },
    onScanSuccess,
    () => {}
).then(() => {
    setStatus('Position QR code within the frame');
}).catch(err => {
    setStatus('Camera error — check permissions', '#dc2626');
    console.error(err);
});
</script>
</body>
</html>
