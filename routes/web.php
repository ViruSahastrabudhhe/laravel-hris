<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Position\PositionController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Deduction\DeductionController;
use App\Http\Controllers\WorkSchedule\WorkScheduleController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Deduction\EmployeeDeductionController;
use App\Http\Controllers\Leave\EmployeeLeaveController;
use App\Http\Controllers\Leave\EmployeeLeaveBalanceController;
use App\Http\Controllers\Leave\HolidayController;
use App\Http\Controllers\Leave\LeaveTypeController;
use App\Http\Controllers\Salary\SalaryController;
use App\Http\Controllers\QrCodeController;

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
    Route::resource('holidays', HolidayController::class);
    Route::put('employee_leaves/{employee_leafe}/approve', [EmployeeLeaveController::class, 'approve'])->name('employee_leaves.approve');
    Route::put('employee_leaves/{employee_leafe}/deny', [EmployeeLeaveController::class, 'deny'])->name('employee_leaves.deny');
    Route::resource('employee_leaves', EmployeeLeaveController::class);
    
    // QR Code Routes
    Route::get('qr-code', [QrCodeController::class, 'index'])->name('qr-code.index');
    Route::post('qr-code/generate', [QrCodeController::class, 'generate'])->name('qr-code.generate');
    Route::get('qr-code/scan', [QrCodeController::class, 'scan'])->name('qr-code.scan');
    Route::get('qr-code/history', [QrCodeController::class, 'history'])->name('qr-code.history');
});