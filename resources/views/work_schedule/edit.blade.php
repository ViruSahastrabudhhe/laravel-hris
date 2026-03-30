@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('work_schedules.index') }}">
        <button>{{ __('schedule.back') }}</button>
    </a>
</div>

<div>
    <form action="{{ route('work_schedules.update', $work_schedule) }}" method="post">
        @csrf
        @method('PUT')
        Schedule name: <input type="text" name="name" value="{{ $work_schedule->name }}" required> <br>
        Start time: <input type="time" name="start_time" value="{{ $work_schedule->start_time }}" required> <br>
        End time: <input type="time" name="end_time" value="{{ $work_schedule->end_time }}" required> <br>
        PM start time: <input type="time" name="pm_start_time" value="{{ $work_schedule->pm_start_time }}" required> <br>
        Break minutes: <input type="number" name="break_minutes" value="{{ $work_schedule->break_minutes }}" required> <br>
        
        Work days: <br>
        <label for="sunday">Sunday</label>
        <input type="checkbox" name="work_days[]" id="sunday" value="Sunday" @checked(in_array('Sunday', $work_schedule->work_days))> <br>
        <label for="monday">Monday</label>
        <input type="checkbox" name="work_days[]" id="monday" value="Monday" @checked(in_array('Monday', $work_schedule->work_days))> <br>
        <label for="tuesday">Tuesday</label>
        <input type="checkbox" name="work_days[]" id="tuesday" value="Tuesday" @checked(in_array('Tuesday', $work_schedule->work_days))> <br>
        <label for="wednesday">Wednesday</label>
        <input type="checkbox" name="work_days[]" id="wednesday" value="Wednesday" @checked(in_array('Wednesday', $work_schedule->work_days))> <br>
        <label for="thursday">Thursday</label>
        <input type="checkbox" name="work_days[]" id="thursday" value="Thursday" @checked(in_array('Thursday', $work_schedule->work_days))> <br>
        <label for="friday">Friday</label>
        <input type="checkbox" name="work_days[]" id="friday" value="Friday" @checked(in_array('Friday', $work_schedule->work_days))> <br>
        <label for="saturday">Saturday</label>
        <input type="checkbox" name="work_days[]" id="saturday" value="Saturday" @checked(in_array('Saturday', $work_schedule->work_days))> <br>
        
        Grace period minutes: <input type="number" name="grace_period_minutes" value="{{ $work_schedule->grace_period_minutes }}" required> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="{{ __('schedule.edit') }}">
    </form>
</div>
@endsection