<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: 'DejaVu Sans', sans-serif; margin: 0; background: #fff; }
    .cert { width: 100%; padding: 60px; text-align: center; border: 12px solid #0b044d; box-sizing: border-box; min-height: 520px; }
    .org { font-size: 13px; color: #9999bb; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
    h1 { font-size: 38px; color: #0b044d; margin: 0 0 6px; }
    .sub { font-size: 14px; color: #6b6a8a; margin-bottom: 32px; }
    .recipient-label { font-size: 12px; color: #9999bb; letter-spacing: 1px; text-transform: uppercase; }
    .recipient { font-size: 30px; font-weight: bold; color: #0b044d; border-bottom: 2px solid #0b044d; display: inline-block; padding: 0 40px 6px; margin: 8px 0 24px; }
    .program-label { font-size: 12px; color: #9999bb; letter-spacing: 1px; text-transform: uppercase; }
    .program { font-size: 20px; font-weight: bold; color: #1a0f6e; margin: 6px 0 32px; }
    .meta { font-size: 12px; color: #9999bb; margin-top: 32px; }
</style>
</head>
<body>
<div class="cert">
    <p class="org">{{ config('app.name') }}</p>
    <h1>Certificate of Completion</h1>
    <p class="sub">This is to certify that</p>
    <p class="recipient-label">Recipient</p>
    <div class="recipient">{{ $employee->first_name }} {{ $employee->last_name }}</div>
    <p class="program-label">has successfully completed</p>
    <p class="program">{{ $training->program_title }}</p>
    <p class="meta">
        Certificate No: {{ $certNo }} &nbsp;·&nbsp;
        Completed: {{ $completionDate ? \Carbon\Carbon::parse($completionDate)->format('F d, Y') : '—' }}
    </p>
</div>
</body>
</html>
