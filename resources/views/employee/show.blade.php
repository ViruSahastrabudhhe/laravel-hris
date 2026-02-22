@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employees.index') }}">
        <button>Back to employees</button>
    </a>
</div>

<div class="container">
    <ul>
        <li>{{ $employee->first_name }} {{ $employee->last_name }}</li>
        <li>Employee ID: {{ $employee->id }}</li>
        <ul>
            <li>Gender: {{ $employee->gender }}</li>
            <li>Email: {{ $employee->email }}</li>
            <li>Date of birth: {{ $employee->date_of_birth }}</li>
            <li>Address: {{ $employee->address }}</li>
            <li>Contact no.: {{ $employee->phone_number }}</li>
            <li>Position: {{ $employee->position->title }}</li>
            <li>Department: {{ $employee->department->name }}</li>
            <li>Salary: {{ $employee->salary }}</li>
            <li>Employment Type: {{ $employee->employment_type }}</li>
            <li>Status: {{ $employee->is_active ? 'Active' : 'Inactive'}}</li>
        </ul>
    </ul>
</div>
@endsection