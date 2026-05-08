<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\EmployeeAttendance;
use App\Models\Position;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\WorkSchedule;
use App\Models\QrAttendanceScan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::get();
        $employeeAttendances = EmployeeAttendance::currentMonth()->get();
        $schedules = WorkSchedule::get();
        $employees = Employee::with(['position', 'department', 'employeeWorkSchedule.workSchedule'])->get();
        $departments = Department::get();
        $positions = Position::get();
        $qrScans = QrAttendanceScan::betweenCurrentMonth()->get()->keyBy('employee_id');
        $totalQrGenerated = $qrScans->count();
        $activeQr = $qrScans->where('valid_until', '>', Carbon::now())->count();

        return view('admin.attendance.index', compact('attendances', 'employeeAttendances', 'departments', 'positions', 'schedules', 'employees', 'qrScans', 'totalQrGenerated', 'activeQr'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attendance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        //
    }

    public function bulkStore(Request $request) {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            $data = array_map('str_getcsv', file($path));

            if (count($data) > 0) {
                unset($data[0]);

                foreach ($data as $row) {
                    Attendance::firstOrCreate(
                        ['employee_id' => $row[7], 'date' => $row[0]],
                        [
                            'date' => $row[0],
                            'time_in' => $row[1],
                            'time_out' => $row[2],
                            'break_start' => $row[3],
                            'break_end' => $row[4],
                            'overtime_in' => $row[5],
                            'overtime_out' => $row[6],
                            'employee_id' => $row[7],
                        ]
                    );
                }
            }
        }

        return redirect()->route('attendances.index')->with('success', __('attendance.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('message', __('attendance.success_deleting'));
    }

    public function bulkDestroy(Request $request)
    {
        Attendance::whereIn('id', $request->ids)->delete();

        return redirect()->route('attendances.index')->with('message', __('attendance.success_deleting'));
    }

    public function restore($attendanceId)
    {
        Attendance::onlyTrashed()->find($attendanceId)->restore();

        return redirect()->route('attendances.archive')->with('success', __('attendance.success_restoring'));
    }

    public function bulkRestore(Request $request)
    {
        Attendance::onlyTrashed()->whereIn('id', $request->ids)->restore();

        return redirect()->route('attendances.index')->with('message', __('attendance.success_restoring'));
    }

    public function archive()
    {
        $attendances = Attendance::onlyTrashed()->get();

        return view('admin.attendance.archive', ['attendances' => $attendances]);
    }

    public function filterEmployeeAttendance(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);
        $isCurrentMonth = $month === now()->month && $year === now()->year;

        if ($isCurrentMonth) {
            $employees = Employee::with(['employeeAttendance'])->get();

            return response()->json([
                'source' => 'live',
                'employees' => $employees->map(fn($e) => [
                    'id' => $e->id,
                    'name' => $e->first_name . ' ' . $e->last_name,
                    'total_present' => $e->employeeAttendance->total_present,
                    'total_late' => $e->employeeAttendance->total_late,
                    'total_absent' => $e->employeeAttendance->total_absent,
                    'is_complete' => $e->employeeAttendance->is_complete,
                ]),
            ]);
        }

        $employeeAttendances = EmployeeAttendance::with(['employee'])
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->diffInWeekdays(Carbon::createFromDate($year, $month, 1)->endOfMonth()) + 1;

        return response()->json([
            'source' => 'snapshot',
            'employees' => $employeeAttendances->map(fn($ea) => [
                'id' => $ea->employee->id,
                'name' => $ea->employee->first_name . ' ' . $ea->employee->last_name,
                'present' => $ea->total_present,
                'late' => $ea->total_late,
                'absent' => $ea->total_absent,
                'ot_hours' => number_format($ea->overtimeMinutes() / 60, 2),
                'is_complete' => $ea->completeAttendances() >= $daysInMonth,
            ]),
        ]);
    }
}
