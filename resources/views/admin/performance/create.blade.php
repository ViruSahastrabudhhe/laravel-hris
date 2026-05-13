@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
    @endphp

@push('styles')
    @vite('resources/css/admin/adminPerformance.css')
@endpush

@section('page-content')
    <h1>Create IPCR Form</h1>

    <form method="POST" action="/performance/ipcr">
        @csrf

        <select name="employee_id">
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
            @endforeach
        </select>

        <select name="performance_cycle_id">
            @foreach($cycles as $cycle)
                <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
            @endforeach
        </select>

        <button type="submit">Create IPCR</button>
    </form>

    <hr>

    <h2>Add KRA Entries</h2>

    <form method="POST" action="/performance/entry">
        @csrf
        <!-- Main Form Identifier -->
        <input name="ipcr_form_id" placeholder="IPCR Form ID" style="margin-bottom: 15px;"><br>

        <table id="kraTable" border="1" style="width: 100%; text-align: left; margin-bottom: 15px;">
            <thead>
            <tr>
                <th>KRA</th>
                <th>Objectives</th>
                <th>Success Indicators</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- Row 1 (Default) -->
            <tr>
                <td><input type="text" name="kra[]" placeholder="KRA" required></td>
                <td><input type="text" name="objectives[]" placeholder="Objectives" required></td>
                <td><input type="text" name="success_indicators[]" placeholder="Success Indicators" required></td>
                <td><button type="button" onclick="removeRow(this)">Delete</button></td>
            </tr>
            </tbody>
        </table>

        <!-- Control Buttons -->
        <button type="button" id="addRowBtn">Add New Row</button>
        <button type="submit">Submit All Rows</button>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('addRowBtn').addEventListener('click', function() {
            // Reference the table body
            const tbody = document.getElementById('kraTable').getElementsByTagName('tbody')[0];

            // Create a new row element
            const newRow = document.createElement('tr');

            // Define the HTML structure for the new row fields
            newRow.innerHTML = `
                <td><input type="text" name="kra[]" placeholder="KRA" required></td>
                <td><input type="text" name="objectives[]" placeholder="Objectives" required></td>
                <td><input type="text" name="success_indicators[]" placeholder="Success Indicators" required></td>
                <td><button type="button" onclick="removeRow(this)">Delete</button></td>
            `;

            // Append the row to the table body
            tbody.appendChild(newRow);
        });

        // Function to delete a row if the user changes their mind
        function removeRow(button) {
            const row = button.closest('tr');
            const tbody = row.parentNode;

            // Prevent deleting the last remaining row if you want to enforce at least one entry
            if (tbody.rows.length > 1) {
                row.remove();
            } else {
                alert("You must keep at least one row.");
            }
        }
    </script>
@endpush
