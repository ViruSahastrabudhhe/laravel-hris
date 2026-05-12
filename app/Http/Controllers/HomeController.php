<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Enums\LeaveStatus;

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
            return view('employee.home');
        }
    }
}
