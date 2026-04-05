@extends('layouts.app')

@section('content')
<div class="auth-root">
    <nav style="padding:18px 64px;background:#fff;border-bottom:1.5px solid #eceaf8;display:flex;align-items:center;justify-content:space-between">
        <div class="pub-logo">
            <div class="pub-logo-seal sm">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <span class="pub-logo-name">PRIME HRIS</span>
                <span class="pub-logo-sub">Pagsanjan, Laguna</span>
            </div>
        </div>
        <a href="{{ route('landing') }}" class="auth-nav-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Home
        </a>
    </nav>

    <div class="auth-body">
        <div class="auth-page-head">
            <span class="pub-eyebrow">ACCOUNT RECOVERY</span>
            <h1 class="auth-page-title">Reset Password</h1>
            <p class="auth-page-sub">Enter your new password below</p>
        </div>

        <div class="auth-card">
            @if ($errors->any())
            <div class="auth-error" style="margin-bottom:18px">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" placeholder="your.email@lgu.gov.ph" required autocomplete="email" autofocus>
                </div>

                <div class="auth-field">
                    <label for="password">New Password</label>
                    <div class="auth-pw-wrap">
                        <input id="password" type="password" name="password" placeholder="Enter new password" required autocomplete="new-password">
                        <button type="button" class="auth-eye" onclick="togglePassword('password', 'eye-icon-1')">
                            <svg id="eye-icon-1" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="auth-field">
                    <label for="password-confirm">Confirm New Password</label>
                    <div class="auth-pw-wrap">
                        <input id="password-confirm" type="password" name="password_confirmation" placeholder="Repeat new password" required autocomplete="new-password">
                        <button type="button" class="auth-eye" onclick="togglePassword('password-confirm', 'eye-icon-2')">
                            <svg id="eye-icon-2" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="pub-btn-primary auth-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <footer class="auth-footer pub-footer">
        <div class="pub-footer-inner">
            <div class="pub-footer-brand">
                <div class="pub-logo-seal sm">
                    <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <span class="pub-footer-name">PRIME HRIS</span>
                    <span class="pub-footer-sub">Pagsanjan, Laguna</span>
                </div>
            </div>
            <p class="pub-footer-copy">© {{ date('Y') }} Municipality of Pagsanjan. All rights reserved.</p>
        </div>
    </footer>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>
@endsection
