<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Enums\CompensationCategory;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Compensation;
use App\Models\Department;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeCompensation;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::currentMonth()->latest()->get();
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
                    $record = Attendance::withTrashed()
                        ->where('employee_id', $row[7])
                        ->whereDate('date', $row[0])
                        ->first();

                    if ($record) {
                        $record->restore();
                        $record;
                    } else {
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
        $data = $request->validated();

        $attendance->update([
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
            'time_in' => $data['time_in'],
            'time_out' => $data['time_out'],
            'break_start' => $data['break_start'],
            'break_end' => $data['break_end'],
            'overtime_in' => $data['overtime_in'],
            'overtime_out' => $data['overtime_out'],
        ]);

        $correction = AttendanceCorrection::firstOrNew([
            'employee_id' => $data['employee_id'],
            'attendance_id' => $attendance->id,
        ]);

        $correction->remarks = $data['correction']['remarks'];

        if ($request->hasFile('correction.proof')) {
            if ($correction->proof) {
                Storage::disk('public')->delete($correction->proof);
            }

            $correction->proof = $request->file('correction.proof')
                ->store('corrections', 'public');
        }

        $correction->save();

        return redirect()->route('attendances.index')->with('success', __('attendance.success_updating'));
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
        foreach($request->ids as $id) {
            $attendance = Attendance::find($id);

            if ($attendance) {
                $attendance->delete();
            }
        }

        return redirect()->route('attendances.index')->with('message', __('attendance.success_deleting'));
    }

    public function restore($attendanceId)
    {
        Attendance::onlyTrashed()->find($attendanceId)->restore();

        return redirect()->route('attendances.archive')->with('success', __('attendance.success_restoring'));
    }

    public function bulkRestore(Request $request)
    {
        foreach($request->ids as $id) {
            $attendance = Attendance::onlyTrashed()->find($id);

            if ($attendance) {
                $attendance->restore();
            }
        }

        return redirect()->route('attendances.index')->with('message', __('attendance.success_restoring'));
    }

    public function archive()
    {
        $attendances = Attendance::onlyTrashed()->get();

        return view('admin.attendance.archive', ['attendances' => $attendances]);
    }

    public function stats(Request $request) {
        $attendances = EmployeeAttendance::where('month', $request->month)
            ->where('year', $request->year)
            ->get();

        return response()->json([
            'total_present'  => $attendances->sum('total_present'),
            'total_late'     => $attendances->sum('total_late'),
            'total_absent'   => $attendances->sum('total_absent'),
            'total_overtime' => $attendances->sum('total_overtime'),
            'stat_month'     => Carbon::createFromDate($request->year, $request->month, 1)->format('F Y'),
        ]);
    }

    public function filterEmployeeAttendance(Request $request)
    {
        $month = (int) $request->integer('month', now()->month);
        $year  = (int) $request->integer('year', now()->year);

        $employeeAttendances = EmployeeAttendance::with([
            'employee.department',
            'employee.position'
        ])
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $employeeAttendances = EmployeeAttendance::with(['employee'])
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return response()->json([
            'source' => 'snapshot',
            'employees' => $employeeAttendances->map(fn($ea) => [
                'id' => $ea->employee->id,
                'first_name' => $ea->employee->first_name,
                'last_name' => $ea->employee->last_name,
                'department' => $ea->employee->department->name,
                'position' => $ea->employee->position->title,
                'present' => $ea->total_present,
                'late' => $ea->total_late,
                'absent' => $ea->total_absent,
                'leaves' => $ea->total_leaves,
                'ot_hours' => $ea->total_overtime / 60,
                'is_complete' => $ea->is_complete,
            ]),
        ]);
    }

    public function filterDetailedAttendance(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $attendances = Attendance::with(['employee'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->latest('date')
            ->get();

        return response()->json([
            'attendances' => $attendances->map(fn($a) => [
                'id' => $a->id,
                'employee' => [
                    'id' => $a->employee->id,
                    'first_name' => $a->employee->first_name,
                    'last_name' => $a->employee->last_name,
                ],
                'date' => Carbon::parse($a->date)->format(config('app.day_month')),
                'time_in' => $a->time_in ?? '--:--',
                'time_out' => $a->time_out ?? '--:--',
                'break_start' => $a->break_start,
                'break_end' => $a->break_end,
                'overtime_in' => $a->overtime_in,
                'overtime_out' => $a->overtime_out,
                'overtime_minutes' => $a->overtime_minutes,
                'total_minutes' => $a->total_minutes,
                'attendance_status' => $a->attendance_status,
                'raw_date' => $a->date,
            ]),
        ]);
    }
}
