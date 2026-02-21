@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employees.index') }}">Back to Employees</a>
</div>

<div>
    <form action="{{ route('employees.store') }}" method='post'>
        @csrf
        First name: <input type="text" name="first_name" required> <br>
        Last name: <input type="text" name="last_name" required> <br>
        Gender: <input type="text" name="gender" required> <br>
        Email: <input type="email" name="email" required> <br>
        Birth date: <input type="date" name="date_of_birth" required> <br>
        Address: <input type="text" name="address" required> <br>
        Contact number: <input type="text" name="phone_number" required> <br>
        Position: <select name="position_id" id="position" required>
                <option value="" readonly>Select position</option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}">{{ $position->title }}</option>
            @endforeach
        </select> <br>
        Department: <select name="department_id" id="departments" required>
                <option value="" readonly>Select department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select> <br>
        Salary grade: <input type="text" name="salary" required> <br>
        Employment type: <input type="text" name="employment_type" required> <br>
        Active: <select name="is_active" id="is_active" required>
            <option value="1" default>Active</option>
            <option value="0">Inactive</option>
        </select> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="submit" value="Create Employee">
    </form>
</div>
@endsection