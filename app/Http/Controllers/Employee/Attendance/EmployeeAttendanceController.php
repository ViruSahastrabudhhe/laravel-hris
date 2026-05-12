<?php

namespace App\Http\Controllers\Employee\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where('user_id', auth()->id())->first();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if (!$employee) {
            return view('employee.attendance.index', [
                'attendances'    => collect(),
                'daysPresent'    => 0,
                'daysAbsent'     => 0,
                'daysLate'       => 0,
                'overtimeHours'  => 0,
                'attendanceRate' => '0%',
                'currentMonth'   => now()->format('F Y'),
                'startDate'      => $request->input('start_date'),
                'endDate'        => $request->input('end_date'),
            ]);
        }

        $query = Attendance::where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            $query->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        } else {
            $query->betweenCurrentMonth();
        }

        $attendances   = $query->orderByDesc('date')->get();
        $daysPresent   = $attendances->whereIn('attendance_status', [AttendanceStatus::Present->value, AttendanceStatus::Late->value])->count();
        $daysAbsent    = $attendances->where('attendance_status', AttendanceStatus::Absent->value)->count();
        $daysLate      = $attendances->where('attendance_status', AttendanceStatus::Late->value)->count();
        $overtimeHours = round($attendances->sum('overtime_minutes') / 60, 1);
        $workingDays   = now()->daysInMonth;
        $attendanceRate = $workingDays > 0 ? round(($daysPresent / $workingDays) * 100) . '%' : '0%';

        $currentMonth = ($startDate || $endDate)
            ? (($startDate ? \Carbon\Carbon::parse($startDate)->format('M d, Y') : '—') . ' to ' . ($endDate ? \Carbon\Carbon::parse($endDate)->format('M d, Y') : '—'))
            : now()->format('F Y');

        return view('employee.attendance.index', compact(
            'attendances', 'daysPresent', 'daysAbsent', 'daysLate',
            'overtimeHours', 'attendanceRate', 'currentMonth', 'startDate', 'endDate'
        ));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
