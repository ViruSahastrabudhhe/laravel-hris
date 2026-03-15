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
                <li>Organization
                    <ul>
                        <li><a href="{{ route('departments.index') }}">Departments</a></li>
                        <li><a href="{{ route('positions.index') }}">Designation</a></li>
                    </ul>
                </li>
                <li>Employees
                    <ul>
                        <li><a href="{{ route('employees.index') }}">Employees List</a></li>
                        <li><a href="{{ route('employees.create') }}">Create Employee</a></li>
                    </ul>
                </li>
                <li>Attendance
                    <ul>
                        <li><a href="{{ route('attendances.index') }}">Attendance List</a></li>
                        <li><a href="{{ route('attendances.create') }}">Create Attendance</a></li>
                    </ul>
                </li>
                <li>Leave
                    <ul>
                        <li><a href="{{ route('employee_leaves.index') }}">Leaves List</a></li>
                        <li><a href="{{ route('employee_leaves.create') }}">Create Leave Application</a></li>
                        <li><a href="{{ route('leave_types.index') }}">Leave Types</a></li>
                        <li><a href="{{ route('holidays.index') }}">Holidays</a></li>
                    </ul>
                </li>
                <li>Deduction
                    <ul>
                        <li><a href="{{ route('deductions.index') }}">Deductions List</a></li>
                        <li><a href="{{ route('deductions.create') }}">Create Deduction</a></li>
                        <li><a href="{{ route('employee_deductions.index') }}">Employee Deductions List</a></li>
                    </ul>
                </li>
                <li>Payroll
                    <ul>
                        <li><a href="{{ route('payroll.index') }}">Payroll List</a></li>
                        <li><a href="#">Generate Payroll</a></li>
                        <li><a href="#">Reports</a></li>
                    </ul>
                </li>
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