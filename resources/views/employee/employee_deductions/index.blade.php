@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employee_deductions.create') }}">
        <button>Add employee deduction</button>
    </a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Deductions</th>
                <th>Total Deductions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>
                    <ul>
                        @forelse($employee->deductions as $deduction)
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
                        @empty
                        <li>No deductions!</li>
                        @endforelse
                    </ul>
                </td>
                <td>P{{ $employee->deductions->sum('amount') }}</td>
            </tr>
            @empty
            <p>No employee deductions!</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection