<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
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

        return view('attendance.index', ['attendances' => $attendances]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendance.create');
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
                            'pm_in' => $row[3],
                            'pm_out' => $row[4],
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
}
