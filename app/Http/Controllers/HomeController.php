<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\EmployeeTraining;
use App\Enums\LeaveStatus;
use App\Enums\EmploymentType;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $month = now()->month;
            $year  = now()->year;

            $employees = Employee::with(['department', 'position'])->get();

            $leaveRequests = LeaveRequest::with('employee')
                ->where('leave_status', LeaveStatus::Pending->value)
                ->latest()
                ->limit(3)
                ->get();

            $departments = Department::withCount('employees')->get();

            $totalGross = 0;
            $totalDeductions = 0;
            $totalNet = 0;

            foreach ($employees as $employee) {
                $totalGross += $employee->grossPay();
                $totalDeductions += $employee->totalDeductions();
                $totalNet += $employee->netPay();
            }

            $attendanceStats = Department::withCount(['employees as attendance_count' => function ($query) use ($month, $year) {
                $query->whereHas('attendance', function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                        ->whereYear('date', $year);
                });
            }])->get();

            $attendanceData = $attendanceStats->pluck('attendance_count');

            return view('admin.home', compact(
                'employees',
                'leaveRequests',
                'departments',
                'totalGross',
                'totalDeductions',
                'totalNet',
                'attendanceData'
            ));
        }

        if (auth()->user()->hasRole('employee')) {
            $user = auth()->user();
            $employee = Employee::where('user_id', $user->id)->first();
            
            if (!$employee) {
                return view('employee.home', [
                    'latestPayslip'  => null,
                    'recentPayslips' => [],
                    'recentLeaves'   => [],
                    'leaveCredits'   => '—',
                    'vacationLeave'  => 0,
                    'sickLeave'      => 0,
                    'attendanceRate' => '—',
                    'isJobOrder'     => false,
                ]);
            }

            $isJobOrder = $employee->employment_type === EmploymentType::JobOrder->value;

            $latestPayslip  = $employee->payrollRecords()->latest()->first();
            $recentPayslips = $employee->payrollRecords()->latest()->limit(5)->get();
            $recentTrainings = $employee->employeeTraining()->with('training')->latest()->limit(3)->get();

            $recentLeaves = [];
            $vacationLeave = 0;
            $sickLeave = 0;
            $totalLeaveCredits = 0;
            if (!$isJobOrder) {
                $recentLeaves  = $employee->leaves()->latest()->limit(3)->get();
                $leaveBalances = $employee->employeeLeaveBalance()->get();
                $vacationLeave = $leaveBalances->where('type', 'vacation')->first()->amount ?? 0;
                $sickLeave     = $leaveBalances->where('type', 'sick')->first()->amount ?? 0;
                $totalLeaveCredits = $vacationLeave + $sickLeave;
            }
            
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $daysWorked = $employee->daysWorked($currentMonth, $currentYear);
            $workingDays = now()->daysInMonth;
            $attendanceRate = $workingDays > 0 ? round(($daysWorked / $workingDays) * 100) . '%' : '—';

            return view('employee.home', [
                'latestPayslip'   => $latestPayslip,
                'recentPayslips'  => $recentPayslips,
                'recentLeaves'    => $recentLeaves,
                'recentTrainings' => $recentTrainings,
                'leaveCredits'    => $totalLeaveCredits > 0 ? $totalLeaveCredits . ' days' : '—',
                'vacationLeave'   => $vacationLeave,
                'sickLeave'       => $sickLeave,
                'attendanceRate'  => $attendanceRate,
                'isJobOrder'      => $isJobOrder,
            ]);
        }
    }
}
