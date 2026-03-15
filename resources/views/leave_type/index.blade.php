@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('leave_types.create') }}">
        <button>Create Leave Type</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Days of Leave</th>
                <th>Is Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveTypes as $leaveType)
                <tr>
                    <td>{{ $leaveType->leave_type }}</td>
                    <td>{{ $leaveType->days_of_leave }}</td>
                    <td>{{ $leaveType->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('leave_types.edit', $leaveType) }}">
                            <button>Edit</button>
                        </a>
                        <form action="{{ route('leave_types.destroy', $leaveType) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            @empty
                <p>No leave types</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
