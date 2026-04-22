<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\QrAttendanceScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['position', 'department'])->get();
        return view('qr_code.index', compact('employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'pm_in' => 'nullable|date_format:H:i',
            'pm_out' => 'nullable|date_format:H:i',
            'overtime_in' => 'nullable|date_format:H:i',
            'overtime_out' => 'nullable|date_format:H:i',
        ]);

        $data = [
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'pm_in' => $request->pm_in,
            'pm_out' => $request->pm_out,
            'overtime_in' => $request->overtime_in,
            'overtime_out' => $request->overtime_out,
            'user_id' => Auth::id(),
        ];

        $hash = hash('sha256', json_encode($data) . time());
        $data['qr_code_hash'] = $hash;

        $qrScan = QrAttendanceScan::create($data);

        $qrData = json_encode([
            'id' => $qrScan->id,
            'hash' => $hash,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
        ]);

        $employee = Employee::find($request->employee_id);

        return view('qr_code.show', compact('qrData', 'qrScan', 'employee'));
    }

    public function scan()
    {
        return view('qr_code.scan');
    }

    public function history()
    {
        $scans = QrAttendanceScan::with(['employee', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('qr_code.history', compact('scans'));
    }
}
