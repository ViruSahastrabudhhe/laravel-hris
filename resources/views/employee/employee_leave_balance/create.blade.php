@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('leave_balances.index') }}">
        <button>Back to employee leave balances</button>
    </a>
</div>

<div>
    <form action="{{ route('leave_balances.store') }}" method="post">
        @csrf
        Employee: <select name="employee_id" id="employee_id" required>
            <option value="">Select employee</option>
            @foreach($employees as $employee)
            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
            @endforeach
        </select> <br>
        Lates: <input type="number" id="lates">
        Absences: <input type="number" id="absences">
        Leave Balance: <input type="number" name="leave_balance" id="leave_balance" required readonly> <br>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" required> <br>
        <input type="submit" value="Create Employee Leave Balance">
    </form>
</div>

<script>
const num3Value = document.getElementById('leave_balance');
num3Value.value = 15;

function calculateSum() {
  // Get the values from the input fields
  const num1Value = document.getElementById('lates').value;
  const num2Value = document.getElementById('absences').value;

  // Convert the values to floating-point numbers (use parseInt() for integers)
  // Use "|| 0" to treat empty or non-numeric values as 0 to prevent NaN errors
  const number1 = (parseFloat(num1Value) || 0) * 0.25;
  const number2 = (parseFloat(num2Value) || 0) * 1;

  // Calculate the sum  
  const sum = number1 + number2;

  num3Value.value -= sum;
}

// Add event listeners to the first two input fields for dynamic updates
document.getElementById('lates').addEventListener('input', calculateSum);
document.getElementById('absences').addEventListener('input', calculateSum);
</script>
@endsection