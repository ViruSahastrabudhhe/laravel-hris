@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('work_schedules.index') }}">
        <button>{{ __('schedule.back') }}</button>
    </a>
</div>

<div>
    <form action="{{ route('work_schedules.store') }}" method="post">
        @csrf
        Schedule name: <input type="text" name="name" required> <br>
        Start time: <input type="time" name="start_time" required> <br>
        End time: <input type="time" name="end_time" required> <br>
        PM start time: <input type="time" name="pm_start_time" required> <br>
        Break minutes: <input type="number" name="break_minutes" required> <br>
        
        Work days: <br>
        <label for="sunday">Sunday</label>
        <input type="checkbox" name="work_days[]" id="sunday" value="Sunday"> <br>
        <label for="monday">Monday</label>
        <input type="checkbox" name="work_days[]" id="monday" value="Monday"> <br>
        <label for="tuesday">Tuesday</label>
        <input type="checkbox" name="work_days[]" id="tuesday" value="Tuesday"> <br>
        <label for="wednesday">Wednesday</label>
        <input type="checkbox" name="work_days[]" id="wednesday" value="Wednesday"> <br>
        <label for="thursday">Thursday</label>
        <input type="checkbox" name="work_days[]" id="thursday" value="Thursday"> <br>
        <label for="friday">Friday</label>
        <input type="checkbox" name="work_days[]" id="friday" value="Friday"> <br>
        <label for="saturday">Saturday</label>
        <input type="checkbox" name="work_days[]" id="saturday" value="Saturday"> <br>
        
        Grace period minutes: <input type="number" name="grace_period_minutes" required> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="{{ __('schedule.create') }}">
    </form>
</div>
@endsection