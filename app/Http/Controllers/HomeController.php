<?php

namespace App\Http\Controllers;

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
            $employees = Employee::with(['department', 'position', 'attendance'])->get();
            $leaveRequests = LeaveRequest::with('employee')->where('leave_status', LeaveStatus::Pending->value)->orderByDesc('created_at')->limit(3)->get();

            return view('admin.home', [
                'employees' => $employees,
                'leaveRequests' => $leaveRequests,
            ]);
        }

        if (auth()->user()->hasRole('employee')) {
            return view('employee.home');
        }
    }
}
