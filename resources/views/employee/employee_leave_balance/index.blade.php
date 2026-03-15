@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('leave_balances.create') }}">
        <button>Add employee leave balance</button>
    </a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Leave Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveBalances as $leaveBalance)
            <tr>
                <td>{{$leaveBalance->employee->first_name}} {{$leaveBalance->employee->last_name}}</td>
                <td>{{$leaveBalance->leave_balance}}</td>
                <td><button>Delete</button></td>
            </tr>
            @empty
            <p>No leave balances!</p>
            @endforelse
        </tbody>
    </table>
</div>
@endsection