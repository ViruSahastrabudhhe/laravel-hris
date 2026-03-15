@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('employee_leaves.index') }}">Back to Employee Leaves</a>
</div>

<div>
    <form action="{{ route('employee_leaves.store') }}" method='post'>
        @csrf
        Employee: <select name="employee_id" id="employee">
            <option value="{{ $employeeLeave->employee->id }}">{{ $employeeLeave->employee->first_name }} {{ $employeeLeave->employee->last_name }}</option>
        </select> <br>

        @if ($leaveTypes->isEmpty())
        Leave Type: <select name="leave_type_id" required>
            <option value="">Select leave type</option>
            <option value="{{ $employeeLeave->leaveType->id }}" selected>{{ $employeeLeave->leaveType->leave_type }}</option>
            <option value="" disabled>-----</option>
            @forelse($leaveTypes as $leaveType)
            <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
            @empty
            <p>A</p>
            @endforelse
        </select> <a href="{{ route('leave_type.create') }}">Add leave types</a> <br>
        @else
        Leave Type: <select name="leave_type_id" required>
            <option value="">Select leave type</option>
            <option value="{{ $employeeLeave->leaveType->id }}" selected>{{ $employeeLeave->leaveType->leave_type }}</option>
            <option value="" disabled>-----</option>
            @forelse($leaveTypes as $leaveType)
            <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
            @empty
            <p>A</p>
            @endforelse
        </select> <br>
        @endif

        Start Date: <input type="date" name="start_date" value="{{ $employeeLeave->start_date }}" required> <br>
        End Date: <input type="date" name="end_date" value="{{ $employeeLeave->end_date }}" required> <br>
        Leave Duration (days): <input type="number" name="leave_duration" value="{{ $employeeLeave->leave_duration }}" required> <br>
        Leave Reason: <textarea name="leave_reason" required>{{ $employeeLeave->leave_reason }}</textarea> <br>
        Leave Status: <select name="leave_status" required id="leave_status">
            <option value="">Select status</option>
            <option value="{{ $employeeLeave->leave_status }}" selected>{{ $employeeLeave->leave_status }}</option>
            <option value="" disabled>-----</option>
            @foreach($leaveStatuses as $leaveStatus)
            <option value="{{ $leaveStatus->name }}">{{ $leaveStatus->name }}</option>
            @endforeach
        </select> <br>
        <div id="decline_reason" style="display: none;">
        Decline Reason: <textarea name="decline_reason" id="decline_reason_input"></textarea>
        </div>
        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
        <input type="submit" value="Create Leave">
    </form>
</div>

<script>
    const selectElement = document.getElementById("leave_status");
    const targetDiv = document.getElementById("decline_reason");

    selectElement.addEventListener("change", (event) => {
    const selectedValue = event.target.value;

    if (selectedValue == "Declined") {
        targetDiv.style.display = "block";
    } else {
        targetDiv.style.display = "none";
    }
    });
</script>
@endsection
