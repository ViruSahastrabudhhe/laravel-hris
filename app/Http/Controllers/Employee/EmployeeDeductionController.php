<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeDeduction;
use App\Models\Employee;
use App\Models\Deduction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeDeductionRequest;
use App\Http\Requests\Employee\UpdateEmployeeDeductionRequest;

class EmployeeDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::findAllWithUserID()->get();
        
        return view('employee.employee_deductions.index', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::findAllWithUserID()->get();  
        $deductions = Deduction::findAllWithUserID()->otherDeductions()->get();

        return view('employee.employee_deductions.create', ['employees' => $employees, 'deductions' => $deductions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeDeductionRequest $request)
    {
        $data = $request->validated();

        EmployeeDeduction::updateOrCreate(
            ['deduction_id' => $data['deduction_id'], 'employee_id' => $data['employee_id']],
            ['amount' => $data['amount'], 'user_id' => $data['user_id']]
        );

        return redirect()->route('employee_deductions.index')->with('success', __('deduction.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeDeduction $employeeDeduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeDeduction $employeeDeduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeDeductionRequest $request, EmployeeDeduction $employeeDeduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeDeduction $employeeDeduction)
    {
        $employeeDeduction->delete();

        return redirect()->route('employee_deductions.index')->with('success', __('deduction.success_deleting'));
    }
}
