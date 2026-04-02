@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employee_deductions.create') }}">
        <button>{{ __('employee_deduction.create') }}</button>
    </a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Mandatory Deductions</th>
                <th>Optional Deductions</th>
                <th>Total Sum of Deductions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>
                    <ul>
                        @forelse($employee->employeeDeduction as $deduction)
                            @if ($deduction->deduction->type=='Mandatory')
                                <li>{{ $deduction->deduction->name }}: P{{ $deduction->amount }}</li>
                            @endif
                        @empty
                        <li>No deductions!</li>
                        @endforelse
                    </ul>
                </td>
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
                            @endif
                        @empty
                        <li>No deductions!</li>
                        @endforelse
                    </ul>
                </td>
                <td>P{{ $employee->employeeDeduction->sum('amount') }}</td>
            </tr>
            @empty
            <p>No employee deductions!</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection