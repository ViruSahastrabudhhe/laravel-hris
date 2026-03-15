@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employees.index') }}">Back to Employees</a>
</div>

<div>
    <form action="{{ route('employees.update', $employee) }}" method='post'>
        @csrf
        @method('put')
        First name: <input type="text" name="first_name" required value="{{ $employee->first_name }}"> <br>
        Last name: <input type="text" name="last_name" required value="{{ $employee->last_name }}"> <br>
        Gender: <input type="text" name="gender" required value="{{ $employee->gender }}"> <br>
        Email: <input type="email" name="email" required value="{{ $employee->email }}"> <br>
        Birth date: <input type="date" name="date_of_birth" required value="{{ $employee->date_of_birth }}"> <br>
        Country: <input type="text" name="address[country]" required value="{{ $employee->address->country }}"> <br>
        Zip code: <input type="number" name="address[zip_code]" required value="{{ $employee->address->zip_code }}"> <br>
        Address: <input type="text" name="address[address]" required value="{{ $employee->address->address }}"> <br>
        City: <input type="text" name="address[city]" required value="{{ $employee->address->city }}"> <br>
        Province: <input type="text" name="address[province]" required value="{{ $employee->address->province }}"> <br>
        Contact number: <input type="text" name="phone_number" required value="{{ $employee->phone_number }}"> <br>
        Position: <select name="position_id" id="position" required value="{{ $employee->position_id }}">
                <option value="">Select position</option>
                <option value="{{ $employee->position_id }}" selected>{{ $employee->position->title }}</option>
                <option value="" disabled>-----</option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}">{{ $position->title }}</option>
            @endforeach
        </select> <br>
        Department: <select name="department_id" id="departments" required>
                <option value="">Select department</option>
                <option value="{{ $employee->department_id }}" selected>{{ $employee->department->name }}</option>
                <option value="" disabled>-----</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select> <br>
        Employment Type: <select name="employment_type" id="employment_type" required>
            <option value="{{ $employee->employment_type }}" selected>{{ $employee->employment_type }}</option>
            <option value="" disabled>-----</option>
            @foreach($employmentTypes as $type)
            <option value="{{ $type->value }}">{{ $type }}</option>
            @endforeach
        </select> <br>
        Active: <select name="is_active" id="is_active" required value="{{ $employee->is_active }}">
            <option value="1" default>Active</option>
            <option value="0">Inactive</option>
        </select> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">
        <input type="submit" value="Edit Employee">
    </form>
</div>
@endsection