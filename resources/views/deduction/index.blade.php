@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('deductions.create') }}">
        <button>Create deduction</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Deduction name</th>
                <th>Deduction rate</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deductions as $deduction)
                <tr>
                    <td>{{ $deduction->name }}</td>
                    <td>{{ $deduction->rate * 100 }}%</td>
                    <td>{{ $deduction->description }}</td>
                    <td>
                        <a href="{{ route('deductions.edit', $deduction) }}">
                            <button>Edit</button>
                        </a>
                        <form action="{{ route('deductions.destroy', $deduction) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            @empty
                <p>No deductions</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection