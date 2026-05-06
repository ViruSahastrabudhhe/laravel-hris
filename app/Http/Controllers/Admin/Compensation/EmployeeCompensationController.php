<?php

namespace App\Http\Controllers\Admin\Compensation;

use App\Models\EmployeeCompensation;
use App\Models\Employee;
use App\Models\Compensation;
use App\Models\Salary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Compensation\StoreEmployeeCompensationRequest;
use App\Http\Requests\Compensation\UpdateEmployeeCompensationRequest;

class EmployeeCompensationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::paginate(25);
        $salaries = Salary::whereHas('employee', fn($q) => $q->where('user_id', auth()->id()))
            ->with('employee.position')
            ->paginate(15);

        return view('employee.employee_compensations.index', compact('employees', 'salaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::paginate(25);
        $compensations = Compensation::otherCompensations()->get();

        return view('employee.employee_compensations.create', ['employees' => $employees, 'compensations' => $compensations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeCompensationRequest $request)
    {
        $data = $request->validated();

        $employee = Employee::with('salary')->findOrFail($data['employee_id']);
        $salary = $employee->salary->amount ?? 0;

        // Sum existing compensations excluding the one being updated (same compensation_id)
        $existingTotal = EmployeeCompensation::where('employee_id', $data['employee_id'])
            ->where('compensation_id', '!=', $data['compensation_id'])
            ->sum('amount');

        if (($salary - ($existingTotal + $data['amount'])) < 0) {
            return back()->withInput()->withErrors([
                'amount' => "This deduction would reduce the employee's salary to a negative amount. Loan rejected."
            ]);
        }

        EmployeeCompensation::updateOrCreate(
            ['compensation_id' => $data['compensation_id'], 'employee_id' => $data['employee_id']],
            ['amount' => $data['amount'], 'user_id' => $data['user_id']]
        );

        return redirect()->route('employee_compensations.index')->with('success', __('deduction.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeCompensation $employeeCompensation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeCompensation $employeeCompensation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeCompensationRequest $request, EmployeeCompensation $employeeCompensation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeCompensation $employeeCompensation)
    {
        $employeeCompensation->delete();

        return redirect()->route('employee_compensations.index')->with('success', __('deduction.success_deleting'));
    }
}
