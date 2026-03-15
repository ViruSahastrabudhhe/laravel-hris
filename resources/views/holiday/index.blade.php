@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('holidays.create') }}">
        <button>Create Holiday</button>
    </a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Holiday Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holidays as $holiday)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $holiday->name }}</td>
                    <td>{{ $holiday->start_date }}</td>
                    <td>{{ $holiday->end_date }}</td>
                    <td>
                        @if ($holiday->holiday_duration <= 1) 
                        {{ $holiday->holiday_duration }} day
                        @else
                        {{ $holiday->holiday_duration }} days
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('holidays.edit', $holiday) }}">
                            <button>Edit</button>
                        </a>
                        <form action="{{ route('holidays.destroy', $holiday) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            @empty
                <p>No holidays</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
