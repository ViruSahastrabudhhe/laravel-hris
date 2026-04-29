<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QrAttendanceScan;
use App\Enums\EmploymentType;
use Illuminate\Http\Request;
use App\Enums\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QrScannerController extends Controller
{
    public function index()
    {
        return view('qr_scanner.index');
    }

    public function scan(Request $request)
    {
        $request->validate(['qr_data' => 'required|string']);

        try {
            $decoded = json_decode($request->qr_data, true);

            if (!isset($decoded['id'], $decoded['hash'], $decoded['employee_id'])) {
                return response()->json(['success' => false, 'message' => 'Invalid QR code format'], 400);
            }

            $qrScan = QrAttendanceScan::with([
                'employee.employeeWorkSchedule.workSchedule'
            ])->find($decoded['id']);

            if (!$qrScan) {
                return response()->json(['success' => false, 'message' => 'QR code not found'], 404);
            }

            if ($qrScan->qr_code_hash !== $decoded['hash']) {
                return response()->json(['success' => false, 'message' => 'Invalid QR code'], 400);
            }

            if ($qrScan->isExpired()) {
                return response()->json(['success' => false, 'message' => 'QR code expired. Please get a new one from admin.'], 400);
            }

            $schedule = $qrScan->employee->employeeWorkSchedule->workSchedule;
            $today = now()->toDateString();
            $now = now();

            $attendance = Attendance::where('employee_id', $qrScan->employee_id)
                ->where('date', $today)
                ->first();

            [$scanType, $status, $overtimeMinutes, $totalMinutes] = $this->resolveScan($attendance, $schedule, $now, $qrScan->employee);

            if (!$scanType) {
                return response()->json(['success' => false, 'message' => 'All scans for today are already completed.'], 400);
            }

            $updateData = [$scanType => $now->format('H:i:s')];

            if ($scanType === 'time_out') {
                $updateData['overtime_minutes'] = $overtimeMinutes;
                $updateData['total_minutes'] = $totalMinutes;
            }

            if ($scanType === 'time_in') {
                $updateData['attendance_status'] = $status->value;
            } elseif ($status === AttendanceStatus::Late) {
                $updateData['attendance_status'] = AttendanceStatus::Late->value;
            }

            $attendance = Attendance::updateOrCreate(
                ['employee_id' => $qrScan->employee_id, 'date' => $today],
                array_merge(['user_id' => 1], $updateData)
            );

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'scan_type' => $scanType,
                'status' => $status->value,
                'employee_name' => $qrScan->employee->first_name . ' ' . $qrScan->employee->last_name,
            ]);

        } catch (\Throwable $e) {
            Log::error('QR scan error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    private function resolveScan(?Attendance $attendance, $schedule, Carbon $now, $employee): array
    {
        $grace = $schedule->grace_period_minutes;
        $date  = today()->toDateString();
        $isJobOrder = $employee->employment_type === EmploymentType::JobOrder->value;

        if (!$attendance?->time_in) {
            $deadline = Carbon::parse($schedule->start_time)->setDateFrom(now())->addMinutes($grace);
            $status = $now->lte($deadline) ? AttendanceStatus::Present : AttendanceStatus::Late;
            return ['time_in', $status, 0, 0];
        }

        if (!$attendance->break_start) {
            $deadline = Carbon::parse("$date 12:00:00")->addMinutes($grace);
            $status = $now->lte($deadline) ? AttendanceStatus::Present : AttendanceStatus::Late;
            return ['break_start', $status, 0, 0];
        }

        if (!$attendance->break_end) {
            $deadline = Carbon::parse($schedule->pm_start_time)->setDateFrom(now())->addMinutes($grace);
            $status = $now->lte($deadline) ? AttendanceStatus::Present : AttendanceStatus::Late;
            return ['break_end', $status, 0, 0];
        }

        if (!$attendance->time_out) {
            $endTime = Carbon::parse($schedule->end_time)->setDateFrom(now());
            $timeIn  = Carbon::parse("$date {$attendance->time_in}");
            $overtimeMinutes = (!$isJobOrder && $now->gt($endTime)) ? (int) $endTime->diffInMinutes($now) : 0;
            $totalMinutes    = (int) $timeIn->diffInMinutes($now) - $schedule->break_minutes;
            return ['time_out', AttendanceStatus::Present, $overtimeMinutes, $totalMinutes];
        }

        return [null, AttendanceStatus::Present, 0, 0];
    }

    public function uploadScan(Request $request)
    {
        try {
            $request->validate([
                'qr_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $image = $request->file('qr_image');
            $imagePath = $image->store('qr_scans', 'public');

            // Here you would integrate with a QR code reader library
            // For now, we'll return the path for manual processing
            // You can use libraries like zxing or similar to decode the image

            return response()->json([
                'success' => true,
                'message' => 'QR image uploaded successfully',
                'image_path' => $imagePath,
                'note' => 'Please decode the QR code and call /api/qr-scanner/scan with the decoded data'
            ], 200);

        } catch (\Exception $e) {
            Log::error('QR Upload Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error uploading QR image: ' . $e->getMessage()
            ], 500);
        }
    }
}
