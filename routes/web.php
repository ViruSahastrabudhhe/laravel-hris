<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\Position\PositionController;
use App\Http\Controllers\Admin\Department\DepartmentController;
use App\Http\Controllers\Admin\Deduction\DeductionController;
use App\Http\Controllers\Admin\WorkSchedule\WorkScheduleController;
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\Payroll\PayrollController;
use App\Http\Controllers\Admin\Deduction\EmployeeDeductionController;
use App\Http\Controllers\Admin\Leave\LeaveRequestController;
use App\Http\Controllers\Admin\Leave\LeaveBalanceController;
use App\Http\Controllers\Admin\Leave\HolidayController;
use App\Http\Controllers\Admin\Leave\LeaveTypeController;
use App\Http\Controllers\Admin\Salary\SalaryController;
use App\Http\Controllers\Admin\QrCode\QrCodeController;
use App\Http\Controllers\Admin\Training\TrainingController;
use App\Http\Controllers\Admin\Api\QrScannerController;
use App\Http\Controllers\Employee\Training\EmployeeTrainingController;
use App\Http\Controllers\Employee\Profile\EmployeeProfileController;
use App\Http\Controllers\Employee\Leave\EmployeeLeaveRequestController;

Auth::routes(['verify' => true]);

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('landing');
    }
});

Route::view('/landing', 'landing')->name('landing');
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function() {
    Route::get('employees/archives', [EmployeeController::class, 'archive'])->name('employees.archive');
    Route::get('employees/check-email', [EmployeeController::class, 'checkEmail'])->name('employees.checkEmail');
    Route::put('employees/{employeeId}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::put('employees/{employeeId}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
    Route::put('employees/{employeeId}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
    Route::resource('employees', EmployeeController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('salaries', SalaryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('attendances/filter', [AttendanceController::class, 'filterByMonth'])->name('attendances.filter');
    Route::post('attendances/store_with_csv', [AttendanceController::class, 'csvStore'])->name('attendances.csvStore');
    Route::delete('attendances/bulk-destroy', [AttendanceController::class, 'bulkDestroy'])->name('attendances.bulkDestroy');
    Route::put('attendances/bulk-restore', [AttendanceController::class, 'bulkRestore'])->name('attendances.bulkRestore');
    Route::get('attendances/archives', [AttendanceController::class, 'archive'])->name('attendances.archive');
    Route::put('attendances/{attendanceId}/restore', [AttendanceController::class, 'restore'])->name('attendances.restore');
    Route::resource('attendances', AttendanceController::class);
    Route::resource('work_schedules', WorkScheduleController::class);
    Route::get('payroll/export-payroll', [PayrollController::class, 'exportPayroll'])->name('payroll.exportPayroll');
    Route::get('payroll/{employee}/export-payslip', [PayrollController::class, 'exportPayslip'])->name('payroll.exportPayslip');
    Route::resource('payroll', PayrollController::class);
    Route::resource('deductions', DeductionController::class);
    Route::resource('employee_deductions', EmployeeDeductionController::class);
    Route::resource('leave_types', LeaveTypeController::class);
    Route::put('leave_requests/{leave_request}/approve', [LeaveRequestController::class, 'approve'])->name('leave_requests.approve');
    Route::put('leave_requests/{leave_request}/deny', [LeaveRequestController::class, 'deny'])->name('leave_requests.deny');
    Route::resource('leave_requests', LeaveRequestController::class);
    Route::get('trainings/{training}/participants', [TrainingController::class, 'participants'])->name('trainings.participants');
    Route::patch('trainings/{employeeTraining}/approve', [TrainingController::class, 'approveParticipant'])->name('trainings.approve');
    Route::patch('trainings/{employeeTraining}/decline', [TrainingController::class, 'declineParticipant'])->name('trainings.decline');
    Route::resource('trainings', TrainingController::class);
    
    Route::get('qr-code', [QrCodeController::class, 'index'])->name('qr-code.index');
    Route::get('qr-code/create', [QrCodeController::class, 'create'])->name('qr-code.create');
    Route::post('qr-code/generate', [QrCodeController::class, 'generate'])->name('qr-code.generate');
    Route::get('qr-code/{qrScan}', [QrCodeController::class, 'show'])->name('qr-code.show');
    Route::get('qr-code/history', [QrCodeController::class, 'history'])->name('qr-code.history');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('qr-code/scan', [QrCodeController::class, 'scan'])->name('qr-code.scan');
    Route::get('qr-scanner', [QrScannerController::class, 'index'])->name('qr-scanner.index');
    Route::post('qr-scanner/process', [QrScannerController::class, 'process'])->name('qr-scanner.process');
});

Route::prefix('employee')->middleware(['auth', 'verified', 'role:employee'])->group(function() {
    Route::resource('profile', EmployeeProfileController::class);
    Route::get('employee_trainings/{employeeTraining}/certificate', [EmployeeTrainingController::class, 'downloadCertificate'])->name('employee_trainings.certificate');
    Route::resource('employee_trainings', EmployeeTrainingController::class);
    Route::resource('employee_leaves', EmployeeLeaveRequestController::class);
});