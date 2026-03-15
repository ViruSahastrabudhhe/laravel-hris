@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('leave_types.index') }}">
        <button>Back to Leave Types</button>
    </a>
</div>

<div>
    <form action="{{ route('leave_types.update', $leaveType) }}" method="post">
        @csrf
        @method('PUT')
        Leave Type: <input type="text" name="leave_type" value="{{ $leaveType->leave_type }}" required> <br>
        Days of Leave: <input type="number" name="days_of_leave" value="{{ $leaveType->days_of_leave }}" required> <br>
        Is Active: <select name="is_active" required>
            <option value="1" @if($leaveType->is_active) selected @endif>Active</option>
            <option value="0" @if(!$leaveType->is_active) selected @endif>Inactive</option>
        </select> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="submit" value="Edit Leave Type">
    </form>
</div>
@endsection
