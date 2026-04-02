@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('attendances.create') }}">
        <button>{{ __('attendance.create') }}</button>
    </a>
    <a href="{{ route('attendances.archive') }}">
        <button>{{ __('attendance.archive') }}</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Break Start</th>
                <th>Break End</th>
                <th>OT In</th>
                <th>OT Out</th>
                <th>Total Minutes</th>
                <th>Total Overtime Minutes</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $attendance->employee->id }}</td>
                <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->time_in }}</td>
                <td>{{ $attendance->time_out }}</td>
                <td>{{ $attendance->break_start }}</td>
                <td>{{ $attendance->break_end }}</td>
                <td>{{ $attendance->overtime_in }}</td>
                <td>{{ $attendance->overtime_out }}</td>
                <td>{{ $attendance->total_minutes }}</td>
                <td>{{ $attendance->overtime_minutes }}</td>
                <td>{{ $attendance->attendance_status }}</td>
                <td>
                    <form action="{{ route('attendances.destroy', $attendance) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="{{ __('common.archive') }}">
                    </form>
                </td>
            </tr>
            @empty
            <p>No attendances</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection