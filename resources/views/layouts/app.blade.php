<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <title>
        @if (Route::currentRouteName() == 'landing') {{ config('app.name') }}
        @else @if (isset($pageTitle)) {{ $pageTitle }} @endif | {{ config('app.name', 'Pagsanjan PRIME-HRIS') }}
        @endif
    </title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite([
        'resources/css/app.css', 
        'resources/css/admin.css',
        'resources/css/jquery.dataTables.min.css',
        'resources/css/datatable.css',
        'resources/js/app.js',
    ])

    @stack('styles')
</head>
<body>
    {{-- dd(Route::currentRouteName()) --}}
    @yield('content')
    @yield('modals')

    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
    <script>
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(m => {
                    m.style.display = 'none';
                });
                document.body.style.overflow = '';
            }
        });

        function escapeRegex(value) {
            return value.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        }
    </script>
    @stack('scripts')
</body>
</html>
