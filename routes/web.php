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

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::prefix('manager')->middleware(['auth', 'verified'])->group(function() {
    Route::get('employees/archives', [EmployeeController::class, 'archive'])->name('employees.archive');
    Route::put('employees/{employeeId}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::resource('employees', EmployeeController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::post('attendances/store_with_csv', [AttendanceController::class, 'csvStore'])->name('attendances.csvStore');
    Route::get('attendances/archives', [AttendanceController::class, 'archive'])->name('attendances.archive');
    Route::put('attendances/{attendanceId}/restore', [AttendanceController::class, 'restore'])->name('attendances.restore');
    Route::resource('work_schedules', WorkScheduleController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payroll', PayrollController::class);
    Route::resource('deductions', DeductionController::class);
    Route::resource('employee_deductions', EmployeeDeductionController::class);
    Route::resource('leave_types', LeaveTypeController::class);
    Route::resource('holidays', HolidayController::class);
    Route::resource('employee_leaves', EmployeeLeaveController::class);
    Route::resource('leave_balances', EmployeeLeaveBalanceController::class);
});