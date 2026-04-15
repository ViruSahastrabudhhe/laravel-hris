<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeLeave;
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
        $employees = Employee::findAllWithUserID()->with(['department', 'position'])->get();
        $employeeLeaves = EmployeeLeave::findAllWithUserID()->with('employee')->where('leave_status', LeaveStatus::Pending->value)->orderByDesc('created_at')->limit(3)->get();

        return view('home', [
            'employees' => $employees,
            'employeeLeaves' => $employeeLeaves,
        ]);
    }
}
