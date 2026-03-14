@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('deductions.index') }}">
        <button>Back to deductions</button>
    </a>
</div>

<div>
    <form action="{{ route('deductions.update', $deduction) }}" method="post">
        @csrf
        @method('PUT')
        Deduction: <input type="text" name="deduction" value="{{ $deduction->name }}" required> <br>
        Rate: <input type="number" name="rate" value="{{ $deduction->rate }}" required> <br>
        Description: <textarea name="description" id="description">{{ $deduction->description }}</textarea>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Create Department">
    </form>
</div>
@endsection