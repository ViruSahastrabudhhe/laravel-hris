<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Address;
use App\Models\Department;
use App\Enums\EmploymentType;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::findAllWithUserID()->get();

        return view('employee.index', ['employees' => $employees]);
    }   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::findAllWithUserID()->get();
        $departments = Department::findAllWithUserID()->get();
        $employmentTypes = EmploymentType::cases();

        return view('employee.create', ['positions' => $positions, 'departments' => $departments, 'employmentTypes' => $employmentTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        $employee = Employee::create($data);

        $employee->address()->create($data['address']);

        return redirect()->route('employees.index')->with('success', __('employee.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employee.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $positions = Position::findAllWithUserID()->get();
        $departments = Department::findAllWithUserID()->get();
        $employmentTypes = EmploymentType::cases();

        return view('employee.edit', 
            [
                'employee' => $employee,
                'positions' => $positions,
                'departments' => $departments,
                'employmentTypes' => $employmentTypes,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        $employee->update($data);

        $employee->address()->update($data['address']);

        return redirect()->route('employees.index')->with('success', __('employee.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', __('employee.success_deleting'));
    }
}
