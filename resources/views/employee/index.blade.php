@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employees.create') }}">
        <button>Add employee</button>
    </a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Salary Grade</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->position_id }}</td>
                <td>{{ $employee->department_id }}</td>
                <td>{{ $employee->salary }}</td>
                <td>{{ $employee->employment_type }}</td>
                <td>{{ $employee->is_active }}</td>
                <td>
                    <button>Edit</button>
                    <form action="{{ route('employees.destroy', $employee) }}" method='post'>
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        @empty
            <tr><p>No employees</p></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection