@extends('layouts.landing')

@section('content')
<div class="auth-root">

    {{-- Gov Bar --}}
    <div class="pub-govbar">
        <span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px">
                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
            </svg>
            Republic of the Philippines &nbsp;·&nbsp; Province of Laguna
        </span>
        <span>Official Website of the Municipal Government of Pagsanjan</span>
    </div>

    {{-- Navbar --}}
    <nav class="pub-nav">
        <div class="pub-logo">
            <div class="pub-logo-seal">
                <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Pagsanjan Logo"
                     onerror="this.style.display='none'"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover">
            </div>
            <div>
                <span class="pub-logo-name">Pagsanjan, Laguna</span>
                <span class="pub-logo-sub">Municipal Government</span>
            </div>
        </div>
        <a href="{{ route('landing') }}" class="auth-nav-back">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Back to Portal
        </a>
    </nav>

    {{-- Body --}}
    <div class="auth-body">

        <div class="auth-page-head">
            <span class="pub-eyebrow">EMPLOYEE PORTAL · PRIME HRIS</span>
            <h1 class="auth-page-title">Create your account</h1>
            <p class="auth-page-sub">Register to access the HRIS system.</p>
        </div>

        <div class="auth-card auth-card-wide">

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

            <form method="POST" action="{{ route('register') }}" class="auth-form" style="display: none;">
                @csrf

                <div class="auth-field">
                    <label for="name">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           placeholder="e.g. Juan Dela Cruz"
                           required autocomplete="name" autofocus>
                    @error('name')
                        <span style="font-size:11.5px;color:#8e1e18;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="e.g. admin@pagsanjan.gov.ph"
                           required autocomplete="email">
                    @error('email')
                        <span style="font-size:11.5px;color:#8e1e18;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-row-2">
                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-pw-wrap">
                            <input id="password" type="password" name="password"
                                   placeholder="Create a password"
                                   required autocomplete="new-password">
                            <button type="button" class="auth-eye" onclick="togglePassword('password','eye-icon-1')">
                                <svg id="eye-icon-1" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span style="font-size:11.5px;color:#8e1e18;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="auth-field">
                        <label for="password-confirm">Confirm Password</label>
                        <div class="auth-pw-wrap">
                            <input id="password-confirm" type="password" name="password_confirmation"
                                   placeholder="Repeat your password"
                                   required autocomplete="new-password">
                            <button type="button" class="auth-eye" onclick="togglePassword('password-confirm','eye-icon-2')">
                                <svg id="eye-icon-2" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="pub-hr-btn auth-submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Create Account
                </button>
            </form>

            <div class="auth-field">
                <p class="auth-switch">
                    Please contact HR to register your account.
                    <a href="{{ route('login') }}" class="auth-switch-btn">Sign in</a>
                </p>
            </div>

            <div class="auth-card-footer" style="display: none;">
                <p class="auth-switch">
                    Already have an account?
                    <a href="{{ route('login') }}" class="auth-switch-btn">Sign in</a>
                </p>
            </div>
        </div>

        {{-- Compliance tags --}}
        <div class="auth-tags">
            <span class="pub-tag">✓ BIR Compliant</span>
            <span class="pub-tag">✓ GSIS Ready</span>
            <span class="pub-tag">✓ RA 10173 Compliant</span>
            <span class="pub-tag">✓ CSC Accredited</span>
        </div>

    </div>

    {{-- Footer --}}
    <footer class="pub-footer auth-footer">
        <div class="pub-footer-inner">
            <div class="pub-footer-brand">
                <div class="pub-logo-seal sm">
                    <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Pagsanjan Logo"
                         onerror="this.style.display='none'"
                         style="width:28px;height:28px;border-radius:50%;object-fit:cover">
                </div>
                <div>
                    <span class="pub-footer-name">Municipal Government of Pagsanjan</span>
                    <span class="pub-footer-sub">Province of Laguna · Republic of the Philippines</span>
                </div>
            </div>
            <p class="pub-footer-copy">© {{ date('Y') }} Municipal Government of Pagsanjan, Laguna. All rights reserved.</p>
        </div>
    </footer>

</div>

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}
</script>
@endpush
@endsection
