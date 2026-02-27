@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('positions.index') }}">
        <button>Back to positions</button>
    </a>
</div>

<div>
    <form action="{{ route('positions.store') }}" method="post">
        @csrf
        Title: <input type="text" name="title" required> <br>
        Salary range: <input type="text" name="salary_grade" required> <br>
        Salary amount: <input type="number" name="salary_amount" required> <br>
        Description: <textarea name="description" id="description"></textarea>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Create Position">
    </form>
</div>
@endsection