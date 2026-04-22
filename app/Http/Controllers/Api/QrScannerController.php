<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QrAttendanceScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QrScannerController extends Controller
{
    public function scan(Request $request)
    {
        try {
            $request->validate([
                'qr_data' => 'required|string',
            ]);

            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['id'], $qrData['hash'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code format'
                ], 400);
            }

            $qrScan = QrAttendanceScan::where('id', $qrData['id'])
                ->where('qr_code_hash', $qrData['hash'])
                ->first();

            if (!$qrScan) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code not found or invalid'
                ], 404);
            }

            if ($qrScan->scanned_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code already scanned',
                    'scanned_at' => $qrScan->scanned_at
                ], 409);
            }

            DB::beginTransaction();

            $qrScan->update([
                'scanned_at' => now(),
                'scanner_ip' => $request->ip(),
            ]);

            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $qrScan->employee_id,
                    'date' => $qrScan->date,
                ],
                [
                    'time_in' => $qrScan->time_in,
                    'time_out' => $qrScan->time_out,
                    'break_start' => $qrScan->pm_out,
                    'break_end' => $qrScan->pm_in,
                    'overtime_in' => $qrScan->overtime_in,
                    'overtime_out' => $qrScan->overtime_out,
                    'user_id' => $qrScan->user_id,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'data' => [
                    'employee_id' => $qrScan->employee_id,
                    'employee_name' => $qrScan->employee->first_name . ' ' . $qrScan->employee->last_name,
                    'date' => $qrScan->date,
                    'time_in' => $qrScan->time_in,
                    'time_out' => $qrScan->time_out,
                    'scanned_at' => $qrScan->scanned_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('QR Scan Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error processing QR code: ' . $e->getMessage()
            ], 500);
        }
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
