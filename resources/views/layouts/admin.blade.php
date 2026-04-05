@extends('layouts.app')

@section('content')
<div class="app-layout">

    <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    <div class="mobile-overlay" id="mobile-overlay"></div>

    @include('partials.sidebar')

    <main class="main-content">
        @if ($errors->any())
        <div class="auth-error" style="margin-bottom:20px">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        @if(session('status'))
        <div class="fp-success" style="margin-bottom:20px">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('status') }}
        </div>
        @endif

        @yield('page-content')
    </main>

</div>

@yield('page-modals')

<script>
const sidebar       = document.getElementById('sidebar');
const toggleBtn     = document.getElementById('toggle-btn');
const logoText      = document.getElementById('logo-text');
const navLabel      = document.getElementById('nav-label');
const userInfo      = document.getElementById('user-info');
const sidebarFooter = document.getElementById('sidebar-footer');
const mobileBtn     = document.getElementById('mobile-menu-btn');
const overlay       = document.getElementById('mobile-overlay');

toggleBtn.addEventListener('click', () => {
    const collapsed = sidebar.classList.toggle('collapsed');
    toggleBtn.textContent = collapsed ? '›' : '‹';
    logoText.style.display  = collapsed ? 'none' : '';
    navLabel.style.display  = collapsed ? 'none' : '';
    userInfo.style.display  = collapsed ? 'none' : '';
    sidebarFooter.classList.toggle('collapsed-footer', collapsed);
    document.querySelectorAll('.nav-label, .nav-active-bar').forEach(el => {
        el.style.display = collapsed ? 'none' : '';
    });
});

mobileBtn.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
});
</script>
@endsection
