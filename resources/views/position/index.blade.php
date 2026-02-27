@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('positions.create') }}">
        <button>Create position</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Position title</th>
                <th>Salary grade</th>
                <th>Salary amount</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($positions as $position)
            <tr>
                <td>{{ $position->title }}</td>
                <td>SG{{ $position->salary_grade }}</td>
                <td>P{{ $position->salary_amount }}</td>
                <td>{{ $position->description }}</td>
                <td>
                    <a href="{{ route('positions.edit', $position) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('positions.destroy', $position) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            @empty
            <p>No positions</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection