@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employees.index') }}">
        <button>{{ __('employee.back') }}</button>
    </a>
</div>

<div class="container">
    <h3>Information</h3>
    <a href="{{ route('employees.edit', $employee) }}">
        {{ __('employee.edit') }}
    </a>
    <ul>
        <li>{{ $employee->first_name }} {{ $employee->last_name }}</li>
        <li>Employee ID: {{ $employee->id }}</li>
        <ul>
            <li>Gender: {{ $employee->gender }}</li>
            <li>Email: {{ $employee->email }}</li>
            <li>Date of birth: {{ $employee->date_of_birth }}</li>
            <li>Address: {{ $employee->address->address }} {{ $employee->address->city }} {{ $employee->address->province }}</li>
            <li>Contact no.: {{ $employee->phone_number }}</li>
            <li>Position: {{ $employee->position->title }}</li>
            <li>Salary Grade: SG{{ $employee->position->salary_grade }}</li>
            <li>Salary: P{{ $employee->position->salary_amount }}</li>
            <li>Department: {{ $employee->department->name }}</li>
            <li>Employment Type: {{ $employee->employment_type }}</li>
            <li>Status: {{ $employee->is_active ? 'Active' : 'Inactive'}}</li>
        </ul>
    </ul>

    <p>Leave Balance: {{ $employee->leaveBalance->leave_balance }}</p>
    <p>Work Schedule: {{ $employee->employeeWorkSchedule->workSchedule->name}}</p>

    <h3>Deductions</h3>
    <table>
        <thead>
            <tr>
                <th>Deduction</th>  
                <th>Total Sum of Deductions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @forelse($employee->employeeDeduction as $deduction)
                         @if ($deduction->deduction->type=='Optional')
                            <li>{{ $deduction->deduction->name }}: P{{ $deduction->amount }}
                                <a
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $deduction->id }}').submit();">
                                    <button>Delete</button>
                                </a> 

                                <form id="delete-form-{{ $deduction->id }}" action="{{ route('employee_deductions.destroy', $deduction) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </li>
                        @else
                            <li>{{ $deduction->deduction->name }}: P{{ $deduction->amount }}</li>
                        @endif    
                        @empty
                        <li>No deductions!</li>
                        @endforelse
                    </ul>
                </td>
                <td>P{{ $employee->employeeDeduction->sum('amount') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection