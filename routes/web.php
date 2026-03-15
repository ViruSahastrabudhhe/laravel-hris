<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Position\PositionController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Deduction\DeductionController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Deduction\EmployeeDeductionController;
use App\Http\Controllers\Leave\EmployeeLeaveController;
use App\Http\Controllers\Leave\EmployeeLeaveBalanceController;
use App\Http\Controllers\Leave\HolidayController;
use App\Http\Controllers\Leave\LeaveTypeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::prefix('email')->middleware(['auth'])->group(function () {
    Route::view('/verify', 'auth.verify')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->name('verification.verify');
    Route::post('verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.resend');
});

Route::prefix('manager')->middleware(['auth', 'verified'])->group(function() {
    Route::resource('employees', EmployeeController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::post('attendances/store_with_csv', [AttendanceController::class, 'csvStore'])->name('attendances.csvStore');
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payroll', PayrollController::class);
    Route::resource('deductions', DeductionController::class);
    Route::resource('employee_deductions', EmployeeDeductionController::class);
    Route::resource('leave_types', LeaveTypeController::class);
    Route::resource('holidays', HolidayController::class);
    Route::resource('employee_leaves', EmployeeLeaveController::class);
    Route::resource('leave_balances', EmployeeLeaveBalanceController::class);
});