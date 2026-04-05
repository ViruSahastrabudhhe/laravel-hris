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
            <span class="pub-eyebrow">EMAIL VERIFICATION</span>
            <h1 class="auth-page-title">Verify Your Email</h1>
            <p class="auth-page-sub">Check your inbox for a verification link</p>
        </div>

        <div class="auth-card">
            @if (session('resent'))
            <div class="fp-success" style="margin-bottom:18px">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                A fresh verification link has been sent to your email address.
            </div>
            @endif

            <div style="background:#f7f6ff;border-radius:12px;padding:18px 20px;margin-bottom:20px;display:flex;align-items:flex-start;gap:14px">
                <svg width="22" height="22" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <div>
                    <p style="font-size:13.5px;font-weight:600;color:#0b044d;margin:0 0 4px">Check your email</p>
                    <p style="font-size:13px;color:#6b6a8a;margin:0;line-height:1.6">Before proceeding, please check your email for a verification link. If you did not receive the email, click the button below to request another.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="pub-btn-primary auth-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    Resend Verification Email
                </button>
            </form>

            <div class="auth-card-footer">
                <p class="auth-switch">
                    Wrong account?
                    <a href="{{ route('logout') }}" class="auth-switch-btn"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                </p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
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
