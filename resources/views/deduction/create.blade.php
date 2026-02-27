@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('deductions.index') }}">
        <button>Back to deductions</button>
    </a>
</div>

<div>
    <form action="{{ route('deductions.store') }}" method="post">
        @csrf
        Deduction: <input type="text" name="deduction" required> <br>
        Rate: <input type="number" name="rate" required> <br>
        Description: <textarea name="description" id="description"></textarea>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Create Department">
    </form>
</div>
@endsection