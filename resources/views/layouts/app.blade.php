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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @yield('content')
    @yield('modals')
    @stack('scripts')
</body>
</html>
