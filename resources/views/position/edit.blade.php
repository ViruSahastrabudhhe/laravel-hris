@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('positions.index') }}">
        <button>Back to positions</button>
    </a>
</div>

<div>
    <form action="{{ route('positions.update', $position) }}" method="post">
        @csrf
        @method('put')
        Title: <input type="text" name="title" value="{{ $position->title }}" required> <br>
        Salary range: <input type="text" name="salary_grade" value="{{ $position->salary_grade }}" required> <br>
        Salary amount: <input type="text" name="salary_amount" value="{{ $position->salary_amount }}" required> <br>
        Description: <textarea name="description" id="description">{{ $position->description }}</textarea>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Edit Position">
    </form>
</div>
@endsection