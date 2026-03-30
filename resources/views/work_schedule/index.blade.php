@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('work_schedules.create') }}">
        <button>{{ __('schedule.create') }}</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Schedule name</th>
                <th>Start time</th>
                <th>End time</th>
                <th>PM start time</th>
                <th>Break minutes</th>
                <th>Work days</th>
                <th>Grace period minutes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $schedule->name }}</td>
                <td>{{ $schedule->start_time }}</td>
                <td>{{ $schedule->end_time }}</td>
                <td>{{ $schedule->pm_start_time }}</td>
                <td>{{ $schedule->break_minutes }}</td>
                <td>
                    @foreach ($schedule->work_days as $day)
                        {{ $day }}@if (!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $schedule->grace_period_minutes }}</td>
                <td>
                    <a href="{{ route('work_schedules.edit', $schedule) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('work_schedules.destroy', $schedule) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            @empty
            <p>No schedules</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection