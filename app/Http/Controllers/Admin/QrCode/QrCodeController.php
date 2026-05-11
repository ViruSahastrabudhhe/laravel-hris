<?php

namespace App\Http\Controllers\Admin\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\QrAttendanceScan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position', 'employeeWorkSchedule.workSchedule'])->get();
        $qrScans   = QrAttendanceScan::whereIn('employee_id', $employees->pluck('id'))
            ->where('expires_at', '>=', now())
            ->get()
            ->keyBy('employee_id');

        $qrData = $qrScans->map(fn($q) => json_encode([
            'id'          => $q->id,
            'hash'        => $q->qr_code_hash,
            'employee_id' => $q->employee_id,
        ]))->toArray();

        return view('admin.qr_code.index', compact('employees', 'qrScans', 'qrData'));
    }

    public function create(Employee $employee)
    {
        $employees = Employee::paginate(25);
        return view('admin.qr_code.create', compact('employee', 'employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::find($request->employee_id);

        $qrScan = QrAttendanceScan::where('employee_id', $employee->id)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$qrScan) {
            $hash = hash('sha256', $employee->id . now()->format('Y-m') . uniqid());
            $qrScan = QrAttendanceScan::create([
                'employee_id'  => $employee->id,
                'qr_code_hash' => $hash,
                'expires_at'   => Carbon::now()->endOfMonth(),
            ]);
        }

        return redirect()->route('qr-code.index')->with('status', 'QR code generated for ' . $employee->first_name . ' ' . $employee->last_name . '.');
    }

    public function show(QrAttendanceScan $qrScan)
    {
        $employee = $qrScan->employee;
        $qrData = json_encode([
            'id' => $qrScan->id,
            'hash' => $qrScan->qr_code_hash,
            'employee_id' => $qrScan->employee_id,
        ]);

        return view('admin.qr_code.show', compact('qrData', 'qrScan', 'employee'));
    }

    public function scan()
    {
        $scans = QrAttendanceScan::with('employee')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        return view('admin.qr_code.scan', compact('scans'));
    }

    public function history()
    {
        $scans = QrAttendanceScan::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.qr_code.history', compact('scans'));
    }
}
