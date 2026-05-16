@extends('layouts.app')

@section('content')
    <div class="app-layout">

        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round">
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <div class="mobile-overlay" id="mobile-overlay"></div>

        @include('partials.sidebar')

        <main class="main-content">
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('errorModal').style.display = 'flex';
                    });
                </script>
            @endif

            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('successModal').style.display = 'flex';
                    });
                </script>
            @endif

            <h1 class="page-header">{{ $pageHeader }}</h1>
            @yield('page-content')

            @include('admin.notification.adminchatnotification')
            @include('admin.chatbot.adminchatbot')
        </main>

    </div>

    @yield('page-modals')

    <div class="modal-overlay" id="successModal" style="display: none;">
        <div class="modal-box">
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div style="display:flex;justify-content:center;padding-top: 1rem;">
                    <svg
                        width="9rem"
                        height="9rem"
                        viewBox="0 0 64 64"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <!-- Green circle background -->
                        <circle cx="32" cy="32" r="30" fill="#22C55E"/>
    
                        <!-- White check -->
                        <path
                            d="M20 33L28 41L45 24"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>
                <div style="text-align:center;padding:1rem;">
                    <h1 style="font-size:1rem;color: #0b044d;margin:0;">{{ session('success') ?? 'Action Success!' }}</h1>
                    <button style="margin-top: 16px;" type="button" class="modal-btn-ghost" onclick="closeModal('successModal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="errorModal" style="display: none;">
        <div class="modal-box">
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div style="display:flex;justify-content:center;padding-top: 1rem;">
                    <svg
                        width="9rem"
                        height="9rem"
                        viewBox="0 0 64 64"
                        fill="none"
                    >
                        <!-- Red circle background -->
                        <circle cx="32" cy="32" r="30" fill="#EF4444"/>

                        <!-- White X -->
                        <path
                            d="M24 24L40 40"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M40 24L24 40"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>
                <div style="text-align:center;padding:1rem;">
                    <div class="auth-error" style="text-align: left;margin-bottom:20px">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <div>
                            @forelse ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @empty
                                <div>An error occurred. Please try again.</div>
                            @endforelse
                        </div>
                    </div>
                    <button type="button" class="modal-btn-ghost" onclick="closeModal('errorModal')">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-btn');
        const logoText = document.getElementById('logo-text');
        const navLabel = document.getElementById('nav-label');
        const userInfo = document.getElementById('user-info');
        const sidebarFooter = document.getElementById('sidebar-footer');
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const overlay = document.getElementById('mobile-overlay');

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('collapsed');
            toggleBtn.textContent = collapsed ? '›' : '‹';
            logoText.style.display = collapsed ? 'none' : '';
            navLabel.style.display = collapsed ? 'none' : '';
            userInfo.style.display = collapsed ? 'none' : '';
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
@endpush
