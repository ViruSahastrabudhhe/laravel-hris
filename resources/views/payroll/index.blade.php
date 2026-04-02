@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Month of {{ $calendar->format('F') }}, {{ $calendar->format('Y') }}</h3>
    <table>
        <thead>
            <tr>
                <th>First day</th>
                <th>Last day</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $calendar->startOfMonth()->toDateString() }}</td>
                <td>{{ $calendar->endOfMonth()->toDateString() }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Hours Worked This Month</th>
                <th>Gross Pay</th>
                <th>GSIS Contribution</th>
                <th>PhilHealth Contribution</th>
                <th>Pag-Ibig Contribution</th>
                <th>Net Taxable Income</th>
                <th>Optional Deductions</th>
                <th>Withholding Tax</th>
                <th>Cash Advance</th>
                <th>Adjustment</th>
                <th>Total Deductions</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->totalHoursWorked() }}</td>
                <td>P{{ $employee->grossPay() }}</td>
                <td>P{{ $employee->gsisContribution()}}</td>
                <td>P{{ $employee->philHealthContribution() }}</td>
                <td>P{{ $employee->pagIbigContribution() }}</td>
                <td>P{{ $employee->netTaxableIncome() }}</td>
                <td>
                    P{{ $employee->optionalDeductions() }}
                    <br>
                    <a href="{{ route('employee_deductions.index') }}">View deductions</a>
                </td>
                <td>P{{ round($employee->withholdingTax(), 2) }}</td>
                <td>-</td>
                <td>-</td>
                <td>P{{ $employee->totalDeductions() }}</td>
                <td>P{{ $employee->netPay() }}</td>
            </tr>
            @empty
            <p>No employees on payroll</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection