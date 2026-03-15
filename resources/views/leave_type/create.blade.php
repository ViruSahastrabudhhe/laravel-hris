@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('leave_types.index') }}">
        <button>Back to Leave Types</button>
    </a>
</div>

<div>
    <form action="{{ route('leave_types.store') }}" method="post">
        @csrf
        Leave Type: <input type="text" name="leave_type" required> <br>
        Days of Leave: <input type="number" name="days_of_leave" required> <br>
        Is Active: <select name="is_active" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select> <br>
        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
        <input type="submit" value="Create Leave Type">
    </form>
</div>
@endsection
