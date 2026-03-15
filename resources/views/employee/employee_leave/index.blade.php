@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employee_leaves.create') }}">
        <button>Add Employee Leave</button>
    </a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($employeeLeaves as $leave)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                <td>{{ $leave->leaveType->leave_type }}</td>
                <td>{{ $leave->start_date }}</td>
                <td>{{ $leave->end_date }}</td>
                <td>{{ $leave->leave_duration }} days</td>
                <td>{{ $leave->leave_reason }}</td>
                <td>{{ $leave->leave_status }}</td>
                <td>
                    <a href="{{ route('employee_leaves.edit', $leave) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('employee_leaves.destroy', $leave) }}" method='post'>
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        @empty
            <tr><p>No employee leaves</p></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
