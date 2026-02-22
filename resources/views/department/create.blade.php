@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('departments.index') }}">
        <button>Back to departments</button>
    </a>
</div>

<div>
    <form action="{{ route('departments.store') }}" method="post">
        @csrf
        Name: <input type="text" name="name" required> <br>
        Description: <textarea name="description" id="description"></textarea>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Create Department">
    </form>
</div>
@endsection