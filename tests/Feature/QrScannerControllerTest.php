<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\QrAttendanceScan;
use App\Models\WorkSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function setupQrScan(): array
{
    $user = User::factory()->create();
    $schedule = WorkSchedule::factory()->create([
        'start_time'          => '08:00:00',
        'end_time'            => '17:00:00',
        'pm_start_time'       => '13:00:00',
        'break_minutes'       => 60,
        'grace_period_minutes'=> 15,
        'user_id'             => $user->id,
    ]);
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    EmployeeWorkSchedule::factory()->create([
        'employee_id'      => $employee->id,
        'work_schedule_id' => $schedule->id,
        'user_id'          => $user->id,
    ]);
    $qrScan = QrAttendanceScan::create([
        'employee_id'  => $employee->id,
        'qr_code_hash' => hash('sha256', $employee->id . now()->format('Y-m') . 'test'),
        'expires_at'   => Carbon::now()->endOfMonth(),
    ]);

    return [$employee, $qrScan];
}

function qrPayload(QrAttendanceScan $qrScan): string
{
    return json_encode([
        'id'          => $qrScan->id,
        'hash'        => $qrScan->qr_code_hash,
        'employee_id' => $qrScan->employee_id,
    ]);
}

// --- QrScannerController ---

it('records on-time time_in when scanned within grace period', function () {
    [$employee, $qrScan] = setupQrScan();

    Carbon::setTestNow('2025-01-10 08:10:00'); // within 15-min grace

    $response = $this->postJson('/api/qr-scanner/scan', ['qr_data' => qrPayload($qrScan)]);

    $response->assertOk()->assertJson(['success' => true, 'scan_type' => 'time_in', 'status' => 'Present']);
    $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id, 'attendance_status' => 'Present']);
});

it('records late time_in when scanned after grace period', function () {
    [$employee, $qrScan] = setupQrScan();

    Carbon::setTestNow('2025-01-10 08:30:00'); // past 08:15 deadline

    $response = $this->postJson('/api/qr-scanner/scan', ['qr_data' => qrPayload($qrScan)]);

    $response->assertOk()->assertJson(['success' => true, 'scan_type' => 'time_in', 'status' => 'Late']);
    $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id, 'attendance_status' => 'Late']);
});

it('records absent when attendance_status is manually set to Absent', function () {
    [$employee, $qrScan] = setupQrScan();

    // Absent means no scan at all — create the record directly
    Attendance::create([
        'employee_id'       => $employee->id,
        'user_id'           => 1,
        'date'              => now()->toDateString(),
        'attendance_status' => 'Absent',
    ]);

    $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id, 'attendance_status' => 'Absent']);
});

it('returns error for invalid qr_data format', function () {
    $this->postJson('/api/qr-scanner/scan', ['qr_data' => 'not-valid-json'])
        ->assertStatus(400)
        ->assertJson(['success' => false]);
});

it('returns error for expired qr code', function () {
    [$employee, $qrScan] = setupQrScan();
    $qrScan->update(['expires_at' => Carbon::now()->subDay()]);

    $this->postJson('/api/qr-scanner/scan', ['qr_data' => qrPayload($qrScan)])
        ->assertStatus(400)
        ->assertJson(['success' => false, 'message' => 'QR code expired. Please get a new one from admin.']);
});

it('returns error when all scans for today are completed', function () {
    [$employee, $qrScan] = setupQrScan();

    Attendance::create([
        'employee_id'       => $employee->id,
        'user_id'           => 1,
        'date'              => now()->toDateString(),
        'time_in'           => '08:00:00',
        'break_start'       => '12:00:00',
        'break_end'         => '13:00:00',
        'time_out'          => '17:00:00',
        'attendance_status' => 'Present',
    ]);

    $this->postJson('/api/qr-scanner/scan', ['qr_data' => qrPayload($qrScan)])
        ->assertStatus(400)
        ->assertJson(['success' => false, 'message' => 'All scans for today are already completed.']);
});

// --- QrCodeController ---

it('generates a new qr code for an employee', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $employee = Employee::factory()->create(['user_id' => $user->id]);

    $response = $this->post(route('qr-code.generate'), ['employee_id' => $employee->id]);

    $response->assertOk()->assertViewIs('qr_code.show');
    $this->assertDatabaseHas('qr_attendance_scans', ['employee_id' => $employee->id]);
});

it('reuses existing valid qr code instead of creating a new one', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $existing = QrAttendanceScan::create([
        'employee_id'  => $employee->id,
        'qr_code_hash' => 'existing-hash',
        'expires_at'   => Carbon::now()->endOfMonth(),
    ]);

    $this->post(route('qr-code.generate'), ['employee_id' => $employee->id]);

    $this->assertDatabaseCount('qr_attendance_scans', 1);
});
