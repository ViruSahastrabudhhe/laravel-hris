<?php

namespace App\Http\Controllers\Employee\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\Employee;
use App\Enums\LeaveStatus;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Leave\StoreEmployeeLeaveRequest;
use App\Http\Requests\Leave\UpdateEmployeeLeaveRequest;

class EmployeeLeaveRequestController extends Controller
{
    public function index() {
        $departments = Department::get();
        $leaveTypes = LeaveType::get();
        $leaveStatuses = LeaveStatus::cases();
        $leaveRequests = LeaveRequest::where('user_id', auth()->id())->get();
        $employee = Employee::where('user_id', auth()->id())->first();

        return view('employee.leave.index', compact('departments', 'leaveTypes', 'leaveStatuses', 'leaveRequests', 'employee'));
    }

    public function create() {}

    public function store(StoreEmployeeLeaveRequest $request) {
        $data = $request->validated();

        LeaveRequest::create([
            'employee_id' => $data['employee_id'],
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'leave_reason' => $data['leave_reason'],
            'leave_status' => LeaveStatus::Pending->value,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('my_leaves.index')->with('success', __('leave_request.success_creating'));
    }

    public function show() {}
    public function edit() {}
    public function update() {}
    public function destroy() {}
}
