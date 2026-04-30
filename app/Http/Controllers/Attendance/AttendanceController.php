<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Attendance;
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
        $attendances = Attendance::findAllWithUserID()->currentMonth()->get();
        $schedules = WorkSchedule::where('user_id', auth()->id())->get();

        $employees = Employee::with(['position', 'department', 'employeeWorkSchedule.workSchedule'])->get();
        $currentMonth = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $qrScans = QrAttendanceScan::whereBetween('created_at', [$currentMonth, $monthEnd])->get()->keyBy('employee_id');
        $totalQrGenerated = $qrScans->count();
        $activeQr = $qrScans->where('valid_until', '>', Carbon::now())->count();

        return view('admin.attendance.index', compact('attendances', 'schedules', 'employees', 'qrScans', 'totalQrGenerated', 'activeQr'));
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

    public function csvStore(Request $request) {
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
                    Attendance::updateOrCreate(
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
                            'user_id' => $row[8]
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
        $attendances = Attendance::findAllWithUserID()->onlyTrashed()->get();

        return view('admin.attendance.archive', ['attendances' => $attendances]);
    }

    public function filterByMonth(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $employees = Employee::with([
            'position', 'department',
            'attendance' => fn($q) => $q->whereYear('created_at', $year)->whereMonth('created_at', $month),
        ])->get();

        $workingDays = \Carbon\Carbon::create($year, $month)->startOfMonth()
            ->diffInWeekdays(\Carbon\Carbon::create($year, $month)->endOfMonth()) + 1;

        return response()->json([
            'employees' => $employees->map(fn($e) => [
                'id'         => $e->id,
                'name'       => $e->first_name . ' ' . $e->last_name,
                'position'   => $e->position->title,
                'department' => $e->department->name,
                'present'    => $e->attendance->where('attendance_status', 'Present')->count(),
                'absent'     => $e->attendance->where('attendance_status', 'Absent')->count(),
                'late'       => $e->attendance->where('attendance_status', 'Late')->count(),
                'ot_hours'   => number_format($e->attendance->sum('overtime_minutes') / 60, 1),
                'complete'   => $e->attendance->count() >= $workingDays,
            ]),
        ]);
    }
}
