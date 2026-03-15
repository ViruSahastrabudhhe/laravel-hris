@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('holidays.index') }}">
        <button>Back to Holidays</button>
    </a>
</div>

<div>
    <form action="{{ route('holidays.update', $holiday) }}" method="post">
        @csrf
        @method('PUT')
        Holiday Name: <input type="text" name="name" value="{{ $holiday->name }}" required> <br>
        Start Date: <input type="date" name="start_date" value="{{ $holiday->start_date }}" required> <br>
        End Date: <input type="date" name="end_date" value="{{ $holiday->end_date }}" required> <br>
        Holiday Duration (days): <input type="number" name="holiday_duration" value="{{ $holiday->holiday_duration }}" required> <br>
        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
        <input type="submit" value="Edit Holiday">
    </form>
</div>
@endsection
