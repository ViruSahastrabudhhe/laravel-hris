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
            <h1 class="auth-page-title">Forgot Password</h1>
            <p class="auth-page-sub">Enter your email and we'll send you a reset link</p>
        </div>

        <div class="auth-card">
            @if (session('status'))
            <div class="fp-success" style="margin-bottom:18px">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('status') }}
            </div>
            @endif

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

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="your.email@lgu.gov.ph" required autocomplete="email" autofocus>
                </div>

                <button type="submit" class="pub-btn-primary auth-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    Send Reset Link
                </button>
            </form>

            <div class="auth-card-footer">
                <p class="auth-switch">
                    Remember your password?
                    <a href="{{ route('login') }}" class="auth-switch-btn">Back to Sign In</a>
                </p>
            </div>
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
@endsection
