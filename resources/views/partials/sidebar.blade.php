@php
    if (auth()->user()->hasRole('admin')) {
        $navItems = [
            ['id' => 'home',        'label' => 'Dashboard',       'route' => 'home',                  'icon' => 'dashboard', 'section' => 'NAVIGATION'],
            ['id' => 'recruitment',   'label' => 'Recruitment',       'route' => 'recruitments.index',       'icon' => 'recruitment', 'section' => null],
            ['id' => 'employees',   'label' => 'Personnel',       'route' => 'employees.index',       'icon' => 'personnel', 'section' => null],
            ['id' => 'training',   'label' => 'Training & Development',       'route' => 'trainings.index',       'icon' => 'training', 'section' => null],
            ['id' => 'attendances', 'label' => 'Time & Attendance',      'route' => 'attendances.index',     'icon' => 'attendance', 'section' => null],
            ['id' => 'leaves',      'label' => 'Leave & Benefits','route' => 'leave_requests.index', 'icon' => 'leave', 'section' => null],
            ['id' => 'performance',   'label' => 'Performance Management',       'route' => 'performance_managements.index',       'icon' => 'performance', 'section' => null],
            ['id' => 'payroll',     'label' => 'Payroll',         'route' => 'payroll.index',         'icon' => 'payroll', 'section' => null],
            ['id' => 'reports',   'label' => 'Reports',       'route' => 'reports.index',       'icon' => 'reports', 'section' => null],
        ];
    } else {
        $navItems = [
            ['id' => 'home',        'label' => 'Dashboard',       'route' => 'home',                  'icon' => 'dashboard', 'section' => 'NAVIGATION'],
            ['id' => 'profile',        'label' => 'Profile',       'route' => 'my_profile.index',                  'icon' => 'profile', 'section' => null],
            ['id' => 'leaves',        'label' => 'Leave & Benefits',       'route' => 'my_leaves.index',                  'icon' => 'leave', 'section' => null],
            ['id' => 'training',        'label' => 'Trainings',       'route' => 'my_trainings.index',                  'icon' => 'training', 'section' => null],
            ['id' => 'attendances',        'label' => 'Attendances',       'route' => 'my_attendances.index',                  'icon' => 'attendance', 'section' => null],
            ['id' => 'performance',        'label' => 'Performance',       'route' => 'my_performance.index',                  'icon' => 'performance', 'section' => null],
            ['id' => 'payslip',        'label' => 'Payslips',       'route' => 'my_payslips.index',                  'icon' => 'payslip', 'section' => null],
        ];
    }

    $currentRoute = Route::currentRouteName();
    $userName = auth()->check() ? auth()->user()->name : 'User';
    $userInitials = auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'U';
    $userRole = auth()->check() ? ucfirst(auth()->user()->getRoleNames()[0]) : 'Guest';
@endphp

<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <a href="{{ route('home') }}">
        <div class="logo">
            <div class="logo-mark">
                <div class="pub-logo-seal sm" style="background:#0b044d">
                    <!-- <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> -->
                    <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Municipal of Pagsanjan Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
                <div class="logo-text-wrap" id="logo-text">
                    <span class="logo-text">{{ config('app.name', 'Prime HRIS') }}</span>
                    <span class="logo-sub">Pagsanjan, Laguna</span>
                </div>
            </div>
        </a>
        <button class="toggle-btn" id="toggle-btn" aria-label="Toggle sidebar">‹</button>
    </div>

    <nav class="sidebar-nav" id="sidebar-nav">
        @foreach($navItems as $item)
        @php
            $isActive = str_starts_with($currentRoute, $item['id']) || $currentRoute === $item['route'];
        @endphp
        @if($item['section'])
        <p class="nav-section-label">{{ $item['section'] }}</p>
        @endif
        <a href="{{ route($item['route']) }}"
           class="nav-item {{ $isActive ? 'active' : '' }}"
           title="{{ $item['label'] }}">
            <span class="nav-icon">
                @if($item['icon'] === 'dashboard')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                @elseif($item['icon'] === 'personnel')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                @elseif($item['icon'] === 'attendance')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                @elseif($item['icon'] === 'leave')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                @elseif($item['icon'] === 'payroll')
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" stroke="none"><text x="7" y="19" font-size="21" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                @elseif($item['icon'] === 'settings')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                @elseif($item['id'] === 'recruitment')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                @elseif($item['id'] === 'training')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                @elseif($item['id'] === 'performance')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                @elseif($item['id'] === 'reports')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                @elseif($item['id'] === 'payslip')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                @elseif($item['id'] === 'profile')
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>                @endif
            </span>
            <span class="nav-label">{{ $item['label'] }}</span>
            @if($isActive)
                <span class="nav-active-bar"></span>
            @endif
        </a>
        @endforeach
    </nav>

    <div class="sidebar-footer" id="sidebar-footer">
        <div class="user-avatar-wrap">
            <div class="user-avatar">{{ $userInitials }}</div>
            <span class="user-status-dot"></span>
        </div>
        <div class="user-info" id="user-info">
            <p class="user-name">{{ $userName }}</p>
            <p class="user-role">{{ $userRole }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn" title="Logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>

</aside>
