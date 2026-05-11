<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pagsanjan PRIME-HRIS') }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite([
        'resources/css/users/usersLanding.css',
        'resources/css/users/usersLogin.css',
        'resources/css/users/usersRegister.css',
        'resources/css/users/usersChatbot.css',
        'resources/js/app.js',
    ])
    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
