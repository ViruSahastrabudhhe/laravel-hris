<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceScanLog;
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

            $employee = $qrScan->employee;

            if ($this->hasRecentScan($employee->id)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate scan detected. Please wait.'
                ], 429);
            }

            $schedule = $employee->employeeWorkSchedule->workSchedule;
            $today = now()->toDateString();
            $now = now();

            $attendance = Attendance::where('employee_id', $qrScan->employee_id)
                ->where('date', $today)
                ->first();

            $scanData = $this->resolveScan($attendance, $schedule, $now);

            $field = $scanData['field'];
            $status = $scanData['status'];

            if (!$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'All scans already completed.'
                ], 400);
            }

            if (!$attendance) {
                $attendance = new Attendance();

                $attendance->employee_id = $employee->id;
                $attendance->date = $today;
                $attendance->number_of_scans = 0;
            }

            $attendance->{$field} = $now->format('H:i:s');

            $attendance->number_of_scans += 1;

            if ($attendance->number_of_scans >= 4) {
                if (!$attendance->time_in || !$attendance->time_out) {
                    $attendance->attendance_status = AttendanceStatus::Absent->value;
                } else {
                    $attendance->attendance_status = $status->value;
                }
                $timeIn = Carbon::parse("$today {$attendance->time_in}");
                $timeOut = Carbon::parse("$today {$attendance->time_out}");
                $attendance->total_minutes = $timeIn->diffInMinutes($timeOut) - $schedule->break_minutes;
                $isJobOrder = $employee->employment_type === EmploymentType::JobOrder->value;
                $endTime = Carbon::parse($schedule->end_time)->setDateFrom(now());
                $attendance->overtime_minutes = (!$isJobOrder && $now->gt($endTime)) ? $endTime->diffInMinutes($now) : 0;
            }

            $attendance->save();

            AttendanceScanLog::create([
                'attendance_id' => $attendance->id,
                'employee_id' => $employee->id,
                'scan_type' => $field,
                'scanned_at' => now(),

                'device_name' => $request->userAgent(),

                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'scan_type' => $field,
                'status' => $status->value,
                'number_of_scans' => $attendance->number_of_scans,
                'date' => $now,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            ]);

        } catch (\Throwable $e) {
            Log::error('QR scan error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    private function resolveScan(?Attendance $attendance, $schedule, Carbon $now): array
    {
        $scanCount = $attendance?->number_of_scans ?? 0;

        switch ($scanCount) {
            case 0:
                return [
                    'field' => 'time_in',
                    'status' => $this->determineStatus(
                        $now,
                        $schedule->start_time,
                        $schedule->grace_period_minutes
                    ),
                ];
            case 1:
                return [
                    'field' => 'break_start',
                    'status' => AttendanceStatus::Present,
                ];
            case 2:
                return [
                    'field' => 'break_end',
                    'status' => AttendanceStatus::Present,
                ];
            case 3:
                return [
                    'field' => 'time_out',
                    'status' =>
                        $attendance->attendance_status ? AttendanceStatus::from($attendance->attendance_status) : AttendanceStatus::Present,
                ];
            default:
                return [
                    'field' => null,
                    'status' => AttendanceStatus::Present,
                ];
        }
    }

    private function determineStatus(Carbon $now, string $startTime, int $grace): AttendanceStatus
    {
        $deadline = Carbon::parse($startTime)
            ->setDateFrom(now())
            ->addMinutes($grace);

        return $now->lte($deadline)
            ? AttendanceStatus::Present
            : AttendanceStatus::Late;
    }

    private function hasRecentScan($employeeId): bool
    {
        $recentScan = AttendanceScanLog::where( 'employee_id', $employeeId)->latest('scanned_at')->first();
        if (!$recentScan) {
            return false;
        }

        return $recentScan->scanned_at->diffInSeconds(now()) < 10;
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
