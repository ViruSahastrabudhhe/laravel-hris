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
        Gender: <select name="gender" id="gender" required>
            <option value="">Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
            <option value="Prefer not to say">Prefer not to say</option>
        </select> <br>
        Email: <input type="email" name="email" required> <br>
        Birth date: <input type="date" name="date_of_birth" required> <br>
        Country: <input type="text" name="address[country]" required> <br>
        Zip code: <input type="number" name="address[zip_code]" required> <br>
        Address: <input type="text" name="address[address]" required> <br>
        City: <input type="text" name="address[city]" required> <br>
        Province: <input type="text" name="address[province]" required> <br>
        Contact number: <input type="text" name="phone_number" required> <br>
        Position: <select name="position_id" id="position" required>
                <option value="">Select position</option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}">{{ $position->title }}</option>
            @endforeach
        </select> <br>
        Department: <select name="department_id" id="departments" required>
                <option value="">Select department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select> <br>
        Employment Type: <select name="employment_type" id="employment_type" required>
            <option value="">Select employment type</option>
            @foreach($employmentTypes as $type)
            <option value="{{ $type->value }}">{{ $type }}</option>
            @endforeach
        </select> <br>
        Status: <select name="is_active" id="is_active" required>
            <option value="1" default>Active</option>
            <option value="0">Inactive</option>
        </select> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">
        <input type="submit" value="Create Employee">
    </form>
</div>
@endsection