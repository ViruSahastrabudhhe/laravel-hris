@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('attendances.create') }}">
        <button>Add employee attendance</button>
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
                <th>PM In</th>
                <th>PM Out</th>
                <th>OT In</th>
                <th>OT Out</th>
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
                <td>{{ $attendance->pm_in }}</td>
                <td>{{ $attendance->pm_out }}</td>
                <td>{{ $attendance->overtime_in }}</td>
                <td>{{ $attendance->overtime_out }}</td>
                <td>
                    <form action="{{ route('attendances.destroy', $attendance) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
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