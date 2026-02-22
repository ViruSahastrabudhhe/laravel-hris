@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('departments.create') }}">
        <button>Create department</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Department name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
                <td>{{ $department->description }}</td>
                <td>
                    <a href="{{ route('departments.edit', $department) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('departments.destroy', $department) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            @empty
            <p>No departments</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection