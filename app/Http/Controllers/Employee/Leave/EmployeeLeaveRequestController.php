<?php

namespace App\Http\Controllers\Employee\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Enums\LeaveStatus;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Leave\StoreEmployeeLeaveRequest;
use App\Http\Requests\Leave\UpdateEmployeeLeaveRequest;

class EmployeeLeaveRequestController extends Controller
{
    public function index() {
        $employee = Employee::where('user_id', auth()->id())->first();

        // Block Job Order employees
        if ($employee && $employee->employment_type === \App\Enums\EmploymentType::JobOrder->value) {
            return redirect()->route('home');
        }

        $leaveTypes    = LeaveType::get();
        $leaveStatuses = LeaveStatus::cases();
        $leaveRequests = LeaveRequest::where('user_id', auth()->id())->get();

        $totalFiled    = $leaveRequests->count();
        $totalDays     = $leaveRequests->sum('leave_duration');
        $totalPending  = $leaveRequests->where('leave_status', LeaveStatus::Pending->value)->count();
        $leaveBalances = EmployeeLeaveBalance::where('employee_id', $employee?->id)->get();

        return view('employee.leave.index', compact(
            'leaveTypes', 'leaveStatuses', 'leaveRequests', 'employee',
            'totalFiled', 'totalDays', 'totalPending', 'leaveBalances'
        ));
    }

    public function create() {}

    public function store(StoreEmployeeLeaveRequest $request) {
        $employee = Employee::where('user_id', auth()->id())->first();
        if ($employee && $employee->employment_type === \App\Enums\EmploymentType::JobOrder->value) {
            return redirect()->route('home');
        }

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
