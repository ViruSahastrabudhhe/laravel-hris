<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name='csrf-token' content='{{ csrf_token() }}'>

    <title>{{ config('app.name', 'Pagsanjan PRIME-HRIS') }} | @if (isset($pageTitle)) {{ $pageTitle }} @endif</title>
</head>
<body>
    <div id='app'>
        <nav>
            <a href="{{ url('/') }}">{{ config('app.name', 'Pagsanjan') }}</a>
            <ul>
                <li><a href="{{ route('home') }}">{{ __('common.app_dashboard') }}</a></li>
                <li>{{ __('common.app_organization') }}
                    <ul>
                        <li><a href="{{ route('departments.index') }}">{{ __('department.sidebar') }}</a></li>
                        <li><a href="{{ route('positions.index') }}">{{ __('position.sidebar') }}</a></li>
                    </ul>
                </li>
                <li>{{ __('common.app_employee') }}
                    <ul>
                        <li><a href="{{ route('employees.index') }}">{{ __('employee.sidebar') }}</a></li>
                        <li><a href="{{ route('employees.create') }}">{{ __('employee.create') }}</a></li>
                    </ul>
                </li>
                <li>{{ __('common.app_schedule') }}
                    <ul>
                        <li><a href="{{ route('work_schedules.index') }}">{{ __('schedule.sidebar') }}</a></li>
                        <li><a href="{{ route('attendances.index') }}">{{ __('attendance.sidebar') }}</a></li>
                        <!-- <li><a href="#">{{ __('employee_attendance.sidebar') }}</a></li> -->
                    </ul>
                </li>
                <li>{{ __('common.app_leave') }}
                    <ul>
                        <li><a href="{{ route('employee_leaves.index') }}">{{ __('employee_leave.sidebar') }}</a></li>
                        <li><a href="{{ route('leave_types.index') }}">{{ __('leave_type.sidebar') }}</a></li>
                        <li><a href="{{ route('holidays.index') }}">{{ __('holiday.sidebar')}}</a></li>
                    </ul>
                </li>
                <li>{{ __('common.app_deduction') }}
                    <ul>
                        <li><a href="{{ route('employee_deductions.index') }}">{{ __('employee_deduction.sidebar') }}</a></li>
                        <li><a href="{{ route('deductions.index') }}">{{ __('deduction.sidebar') }}</a></li>
                        <li><a href="{{ route('deductions.create') }}">{{ __('deduction.create') }}</a></li>
                    </ul>
                </li>
                <li>{{ __('common.app_payroll') }}
                    <ul>
                        <li><a href="{{ route('payroll.index') }}">{{ __('payroll.sidebar') }}</a></li>
                        <li><a href="#">{{ __('payroll.create') }}</a></li>
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
            
            @if(isset($pageHeader))
                <h1>{{ $pageHeader }}</h1>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>