<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PayrollRecord;
use App\Models\LeaveRequest;
use App\Models\Training;
use App\Models\Department;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $now   = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        // ── Employees ──────────────────────────────────────────────
        $totalEmployees  = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->count();
        $departments     = Department::withCount('employees')->get();

        // ── Attendance (current month) ──────────────────────────────
        $attendances     = Attendance::currentMonth()->get();
        $totalPresent    = $attendances->where('attendance_status', 'Present')->count();
        $totalLate       = $attendances->where('attendance_status', 'Late')->count();
        $totalAbsent     = $attendances->where('attendance_status', 'Absent')->count();
        $totalOvertimeHrs = round($attendances->sum('overtime_minutes') / 60, 1);

        // ── Payroll (current month) ─────────────────────────────────
        $payrollRecords   = PayrollRecord::where('month', $month)->where('year', $year)->get();
        $totalGross       = $payrollRecords->sum('total_earnings');
        $totalDeductions  = $payrollRecords->sum('total_deductions');
        $totalNetPay      = $payrollRecords->sum('amount_paid');
        $processedPayroll = $payrollRecords->where('status', 'Processed')->count();

        // ── Leave ───────────────────────────────────────────────────
        $leaveRequests  = LeaveRequest::all();
        $pendingLeaves  = $leaveRequests->where('leave_status', 'Pending')->count();
        $approvedLeaves = $leaveRequests->where('leave_status', 'Approved')->count();
        $declinedLeaves = $leaveRequests->where('leave_status', 'Declined')->count();

        // ── Training ────────────────────────────────────────────────
        $trainings          = Training::all();
        $ongoingTrainings   = $trainings->where('status', 'Ongoing')->count();
        $completedTrainings = $trainings->where('status', 'Completed')->count();
        $totalParticipants  = $trainings->sum('participants');

        return view('admin.report.index', compact(
            'now', 'month', 'year',
            'totalEmployees', 'activeEmployees', 'departments',
            'totalPresent', 'totalLate', 'totalAbsent', 'totalOvertimeHrs',
            'totalGross', 'totalDeductions', 'totalNetPay', 'processedPayroll',
            'pendingLeaves', 'approvedLeaves', 'declinedLeaves',
            'trainings', 'ongoingTrainings', 'completedTrainings', 'totalParticipants'
        ));
    }

    public function create() {}
    public function store(\Illuminate\Http\Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(\Illuminate\Http\Request $request, $id) {}
    public function destroy($id) {}
}
