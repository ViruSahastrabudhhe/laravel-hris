<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name='csrf-token' content='{{ csrf_token() }}'>

    <title>{{ config('app.name', 'Pagsanjan') }}</title>
</head>
<body>
    <div id='app'>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Dashboard</a></li>
                <li>
                    <a href="{{ route('employees.index') }}">Employees</a>
                    <ul>
                        <li><a href="{{ route('employee_deductions.index') }}">Employee deductions</a></li>
                        <li><a href="#">Employee leaves</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('positions.index') }}">Positions</a></li>
                <li><a href="{{ route('departments.index') }}">Departments</a></li>
                <li><a href="{{ route('attendances.index') }}">Attendance</a></li>
                <li><a href="{{ route('payroll.index') }}">Payroll</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </nav>

        <div>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
            </form>
        </div>

        <div>
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
        </div>

        <main>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>

</body>
</html>