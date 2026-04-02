@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employee_deductions.index') }}">
        <button>{{ __('employee_deduction.back') }}</button>
    </a>
</div>

<div>
    <form action="{{ route('employee_deductions.store') }}" method="post">
        @csrf
        Employee: <select name="employee_id" id="employee" required>
            <option value="">Select Employee</option>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
        @endforeach
        </select> <br>
        Department: <select name="deduction_id" id="deduction" required>
            <option value="">Select deduction</option>
        @foreach($deductions as $deduction)
            <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
        @endforeach
        </select> <br>
        Amount: P<input type="number" step="any" name="amount" required> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="{{ __('employee_deduction.create') }}">
    </form>
</div>
@endsection