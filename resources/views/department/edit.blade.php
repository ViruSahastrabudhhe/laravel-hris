@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('departments.index') }}">
        <button>Back to departments</button>
    </a>
</div>

<div>
    <form action="{{ route('departments.update', $department) }}" method="post">
        @csrf
        @method('put')
        Name: <input type="text" name="name" value="{{ $department->name }}" required> <br>
        Description: <input type="text" name="description" value="{{ $department->description }}" required> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Edit Department">
    </form>
</div>
@endsection