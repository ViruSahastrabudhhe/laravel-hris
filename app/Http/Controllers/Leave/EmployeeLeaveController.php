<?php

namespace App\Http\Controllers\Leave;

use App\Models\EmployeeLeave;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Enums\LeaveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreEmployeeLeaveRequest;
use App\Http\Requests\Leave\UpdateEmployeeLeaveRequest;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeLeaves = EmployeeLeave::findAllWithUserID()->get();

        return view('employee.employee_leave.index', ['employeeLeaves' => $employeeLeaves]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveTypes = LeaveType::findAllWithUserID()->get();
        $leaveStatuses = LeaveStatus::cases();
        $employees = Employee::findAllWithUserID()->get();

        return view('employee.employee_leave.create', ['leaveTypes' => $leaveTypes, 'leaveStatuses' => $leaveStatuses, 'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeLeaveRequest $request)
    {
        $data = $request->validated();

        EmployeeLeave::create($data);

        return redirect()->route('employee_leaves.index')->with('success', __('employee.employee_leave.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeLeave $employee_leafe)
    {
        return redirect()->route('employee_leaves.index')->with('success', __('employee.employee_leave.show_not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeLeave $employee_leafe)
    {
        $leaveTypes = LeaveType::findAllWithUserID()->get();
        $leaveStatuses = LeaveStatus::cases();

        return view('employee.employee_leave.edit', ['employeeLeave' => $employee_leafe, 'leaveTypes' => $leaveTypes, 'leaveStatuses' => $leaveStatuses]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeLeaveRequest $request, EmployeeLeave $employee_leafe)
    {
        $data = $request->validated();

        $employee_leafe->update($data);

        return redirect()->route('employee_leaves.index')->with('success', __('employee.employee_leave.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLeave $employee_leafe)
    {
        $employee_leafe->delete();

        return redirect()->route('employee_leaves.index')->with('success', __('employee.employee_leave.success_deleting'));
    }
}
