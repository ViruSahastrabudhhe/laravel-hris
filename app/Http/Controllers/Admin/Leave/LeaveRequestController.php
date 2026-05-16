<?php

namespace App\Http\Controllers\Admin\Leave;

use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Http\Requests\Leave\UpdateLeaveRequest;
use App\Models\Compensation;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\PayPeriod;
use App\Models\Holiday;
use App\Models\EmployeeCompensation;
use App\Enums\LeaveStatus;
use App\Enums\CompensationCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreEmployeeLeaveRequest;
use App\Http\Requests\Leave\UpdateEmployeeLeaveRequest;
use App\Http\Requests\Leave\DenyLeaveRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::with(
            [
                'employee:id,first_name,last_name', 
                'leaveType:id,name', 
            ])
            ->latest()
            ->paginate(25);

        $leaveStatuses = LeaveStatus::cases();
        $leaveTypes = LeaveType::paginate(25);

        $leaveStats = Cache::remember('leave_stats', 300, fn() => [
            'total_leaves'     => LeaveRequest::count(),
            'total_approved'   => LeaveRequest::where('leave_status', LeaveStatus::Approved->value)->count(),
            'total_pending'    => LeaveRequest::where('leave_status', LeaveStatus::Pending->value)->count(),
            'total_leave_days' => LeaveRequest::sum('leave_duration'),
        ]);

        $leaveTypeStats = Cache::remember('leave_type_stats', 300, fn() => [
            'total'      => LeaveType::count(),
            'active'     => LeaveType::where('is_active', true)->count(),
            'inactive'   => LeaveType::where('is_active', false)->count(),
            'total_days' => LeaveType::sum('days_of_leave'),
        ]);

        $compensationStats = Cache::remember('compensation_stats', 300, fn() => [
            'total_benefits'   => EmployeeCompensation::count(),
            'total_earnings'   => EmployeeCompensation::whereHas('compensation', fn($q) =>
            $q->where('category', CompensationCategory::Earning->value)
            )->sum('amount'),
            'total_deductions' => EmployeeCompensation::whereHas('compensation', fn($q) =>
            $q->where('category', CompensationCategory::Deduction->value)
            )->sum('amount'),
        ]);

        $employees = Employee::with(['employeeCompensation.payPeriod'])->get();
        $compensations = Compensation::all();
        $periods = PayPeriod::where('month', now()->month)
            ->where('year', now()->year)
            ->get();

        return view('admin.leave.index', compact(
            'leaveRequests',
            'leaveStatuses',
            'leaveTypes',
            'leaveStats',
            'leaveTypeStats',
            'compensationStats',
            'employees',
            'compensations',
            'periods',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveTypes = LeaveType::paginate(25);
        $leaveStatuses = LeaveStatus::cases();
        $employees = Employee::paginate(25);

        return view('admin.leave.create', ['leaveTypes' => $leaveTypes, 'leaveStatuses' => $leaveStatuses, 'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        $data = $request->validated();

        LeaveRequest::create($data);

        return redirect()->route('leave_requests.index')->with('success', __('leave_request.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return redirect()->route('leave_requests.index')->with('success', __('leave_request.show_not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $leaveTypes = LeaveType::paginate(25);
        $leaveStatuses = LeaveStatus::cases();

        return view('admin.leave.edit', ['employeeLeave' => $leaveRequest, 'leaveTypes' => $leaveTypes, 'leaveStatuses' => $leaveStatuses]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, LeaveRequest $leaveRequest)
    {
        $data = $request->validated();

        $leaveRequest->update($data);

        return redirect()->route('leave_requests.index')->with('success', __('leave_request.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('leave_requests.index')->with('success', __('leave_request.success_deleting'));
    }

    public function archive()
    {
        $archivedLeaves = LeaveRequest::onlyTrashed()->with(['employee.department', 'leaveType'])->get();
        $total = $archivedLeaves->count();

        return view('admin.leave.archive', compact('archivedLeaves', 'total'));
    }

    public function restore($id)
    {
        LeaveRequest::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('leave_requests.archive')->with('success', __('leave_request.success_restoring'));
    }

    public function approve(LeaveRequest $leaveRequest) {
        $leaveRequest->update(['leave_status' => LeaveStatus::Approved->value]);

        return redirect()->route('leave_requests.index')->with('success', __('leave_request.success_approving'));
    }

    public function deny(LeaveRequest $leaveRequest, DenyLeaveRequest $request) {
        $data = $request->validated();

        $leaveRequest->update([
            'leave_status' => LeaveStatus::Declined->value,
            'decline_reason' => $data['decline_reason'],
        ]);

        return redirect()->route('leave_requests.index')->with('success', __('leave_request.success_denying'));
    }
}
