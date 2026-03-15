<?php

namespace App\Http\Controllers\Leave;

use App\Models\EmployeeLeaveBalance;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreEmployeeLeaveBalanceRequest;
use App\Http\Requests\Leave\UpdateEmployeeLeaveBalanceRequest;

class EmployeeLeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveBalances = EmployeeLeaveBalance::findAllWithUserID()->get();
        $employees = Employee::findAllWithUserID()->get();

        return view('employee.employee_leave_balance.index', ['leaveBalances' => $leaveBalances, 'employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::findAllWithUserID()->get();

        return view('employee.employee_leave_balance.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeLeaveBalanceRequest $request)
    {
        $data = $request->validated();

        EmployeeLeaveBalance::create($data);

        return redirect()->route('leave_balances.index')->with('success', __('leave_balance.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeLeaveBalance $employeeLeaveBalance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeLeaveBalance $employeeLeaveBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeLeaveBalanceRequest $request, EmployeeLeaveBalance $employeeLeaveBalance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLeaveBalance $employeeLeaveBalance)
    {
        $employeeLeaveBalance->delete();

        return redirect()->route('leave_balances.index')->with('success', __('leave_balance.success_deleting'));
    }
}
