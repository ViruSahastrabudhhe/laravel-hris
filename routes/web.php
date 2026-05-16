<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\Position\PositionController;
use App\Http\Controllers\Admin\Department\DepartmentController;
use App\Http\Controllers\Admin\Compensation\CompensationController;
use App\Http\Controllers\Admin\WorkSchedule\WorkScheduleController;
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\Payroll\PayrollRecordController;
use App\Http\Controllers\Admin\Compensation\EmployeeCompensationController;
use App\Http\Controllers\Admin\Leave\LeaveRequestController;
use App\Http\Controllers\Admin\Leave\EmployeeLeaveBalanceController;
use App\Http\Controllers\Admin\Leave\LeaveTypeController;
use App\Http\Controllers\Admin\Salary\SalaryController;
use App\Http\Controllers\Admin\QrCode\QrCodeController;
use App\Http\Controllers\Admin\Training\TrainingController;
use App\Http\Controllers\Admin\Api\QrScannerController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Audit\AuditController;
use App\Http\Controllers\Chatbot\ChatbotController;
use App\Http\Controllers\Admin\Recruitment\RecruitmentController;
use App\Http\Controllers\Admin\Performance\PerformanceManagementController;
use App\Http\Controllers\Employee\Training\EmployeeTrainingController;
use App\Http\Controllers\Employee\Profile\EmployeeProfileController;
use App\Http\Controllers\Employee\Leave\EmployeeLeaveRequestController;
use App\Http\Controllers\Employee\Attendance\EmployeeAttendanceController;
use App\Http\Controllers\Employee\Performance\EmployeePerformanceController;
use App\Http\Controllers\Employee\Payslip\EmployeePayslipController;

Auth::routes(['verify' => true]);

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('landing');
    }
});

Route::view('/landing', 'landing')->name('landing');
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified', 'active'])->name('home');

Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function() {
    Route::get('employees/archives', [EmployeeController::class, 'archive'])->name('employees.archive');
    Route::get('employees/check-email', [EmployeeController::class, 'checkEmail'])->name('employees.checkEmail');
    Route::post('employees/bulk-store', [EmployeeController::class, 'bulkStore'])->name('employees.bulkStore');
    Route::put('employees/{employeeId}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::put('employees/{employeeId}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
    Route::put('employees/{employeeId}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
    Route::resource('employees', EmployeeController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('salaries', SalaryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('attendances/stats', [AttendanceController::class, 'stats'])->name('attendances.stats');
    Route::get('attendances/filter/employee_attendance', [AttendanceController::class, 'filterEmployeeAttendance'])->name('attendances.filterEmployeeAttendance');
    Route::get('attendances/filter/detailed', [AttendanceController::class, 'filterDetailedAttendance'])->name('attendances.filterDetailedAttendance');
    Route::post('attendances/store_with_csv', [AttendanceController::class, 'bulkStore'])->name('attendances.bulkStore');
    Route::delete('attendances/bulk-destroy', [AttendanceController::class, 'bulkDestroy'])->name('attendances.bulkDestroy');
    Route::put('attendances/bulk-restore', [AttendanceController::class, 'bulkRestore'])->name('attendances.bulkRestore');
    Route::get('attendances/archives', [AttendanceController::class, 'archive'])->name('attendances.archive');
    Route::put('attendances/{attendanceId}/restore', [AttendanceController::class, 'restore'])->name('attendances.restore');
    Route::resource('attendances', AttendanceController::class);
    Route::resource('work_schedules', WorkScheduleController::class);
    Route::resource('compensations', CompensationController::class);
    Route::resource('employee_compensations', EmployeeCompensationController::class);
    Route::resource('leave_types', LeaveTypeController::class);
    Route::prefix('leave_requests')->group(function() {
       Route::get('/', [LeaveRequestController::class, 'index'])->name('leave_requests.index');
       Route::post('/store', [LeaveRequestController::class, 'store'])->name('leave_requests.store');
       Route::put('/update/{leave_request}', [LeaveRequestController::class, 'update'])->name('leave_requests.update');
       Route::delete('/delete/{leave_request}', [LeaveRequestController::class, 'destroy'])->name('leave_requests.destroy');
       Route::get('/archives', [LeaveRequestController::class, 'archive'])->name('leave_requests.archive');
       Route::put('/restore/{leave_request}', [LeaveRequestController::class, 'restore'])->name('leave_requests.restore');
       Route::put('/approve/{leave_request}', [LeaveRequestController::class, 'approve'])->name('leave_requests.approve');
       Route::put('/deny/{leave_request}', [LeaveRequestController::class, 'deny'])->name('leave_requests.deny');
    });
    Route::get('trainings/{training}/participants', [TrainingController::class, 'participants'])->name('trainings.participants');
    Route::patch('trainings/{employeeTraining}/approve', [TrainingController::class, 'approveParticipant'])->name('trainings.approve');
    Route::patch('trainings/{employeeTraining}/decline', [TrainingController::class, 'declineParticipant'])->name('trainings.decline');
    Route::resource('trainings', TrainingController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('recruitments', RecruitmentController::class);
    Route::prefix('performance_management')->group(function () {
        Route::get('/', [PerformanceManagementController::class, 'index'])->name('performance_management.index');
        Route::post('/store/performance_cycle', [PerformanceManagementController::class, 'storePerformanceCycle'])->name('performance_management.storePerformanceCycle');
        Route::post('/store/ipcr_form', [PerformanceManagementController::class, 'storeIpcr'])->name('performance_management.storeIPCRForm');
        Route::post('/bulk-store/ipcr_form', [PerformanceManagementController::class, 'bulkStoreIpcr'])->name('performance_management.bulkStoreIPCRForm');
        Route::post('/submit/{form}', [PerformanceManagementController::class, 'submit']);
        Route::put('/entries/update', [PerformanceManagementController::class, 'updateIpcr'])->name('performance_management.updateIPCRForm');
        Route::put('/compute/{form}', [PerformanceManagementController::class, 'computeFinalRating'])->name('performance_management.computeFinalRating');
        Route::put('/activate/{cycle}', [PerformanceManagementController::class, 'activateCycle'])->name('performance_management.activateCycle');
        Route::put('/deactivate/{cycle}', [PerformanceManagementController::class, 'deactivateCycle'])->name('performance_management.deactivateCycle');
        Route::put('/approve/{form}', [PerformanceManagementController::class, 'approve'])->name('performance_management.approve');
        Route::delete('/delete/{form}', [PerformanceManagementController::class, 'destroy'])->name('performance_management.destroy');
        Route::delete('/delete/{cycle}', [PerformanceManagementController::class, 'destroyCycle'])->name('performance_management.destroyCycle');
    });
    Route::prefix('payroll')->group(function() {
        Route::get('/', [PayrollRecordController::class, 'index'])->name('payroll.index');
        Route::get('/filter', [PayrollRecordController::class, 'filter'])->name('payroll.filter');
        Route::post('/store/record', [PayrollRecordController::class, 'storeRecord'])->name('payroll.storeRecord');
        Route::post('/store/period', [PayrollRecordController::class, 'storePeriod'])->name('payroll.storePeriod');
        Route::post('/bulk-store/record', [PayrollRecordController::class, 'bulkStoreRecord'])->name('payroll.bulkStoreRecord');
        Route::post('/quick-store/period', [PayrollRecordController::class, 'quickStorePeriod'])->name('payroll.quickStorePeriod');
        Route::put('/activate/{period}', [PayrollRecordController::class, 'activatePeriod'])->name('payroll.activatePeriod');
        Route::put('/deactivate/{period}', [PayrollRecordController::class, 'deactivatePeriod'])->name('payroll.deactivatePeriod');
        Route::get('/export-payroll', [PayrollRecordController::class, 'exportPayroll'])->name('payroll.exportPayroll');
        Route::get('/export-payslip/{employee}', [PayrollRecordController::class, 'exportPayslip'])->name('payroll.exportPayslip');
        Route::delete('/delete/{period}', [PayrollRecordController::class, 'destroyPeriod'])->name('payroll.destroyPeriod');
    });
    Route::resource('audits', AuditController::class);

    Route::get('qr-code', [QrCodeController::class, 'index'])->name('qr-code.index');
    Route::get('qr-code/create', [QrCodeController::class, 'create'])->name('qr-code.create');
    Route::post('qr-code/generate', [QrCodeController::class, 'generate'])->name('qr-code.generate');
    Route::get('qr-code/{qrScan}', [QrCodeController::class, 'show'])->name('qr-code.show');
    Route::get('qr-code/history', [QrCodeController::class, 'history'])->name('qr-code.history');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('qr-code/scan', [QrCodeController::class, 'scan'])->name('qr-code.scan');
    Route::get('qr-scanner', [QrScannerController::class, 'index'])->name('qr-scanner.index');
    Route::post('qr-sanner/process', [QrScannerController::class, 'process'])->name('qr-scanner.process');
});

Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

Route::prefix('employee')->middleware(['auth', 'verified', 'role:employee', 'active'])->group(function() {
    Route::resource('my_profile', EmployeeProfileController::class);
    Route::get('my_trainings/{employeeTraining}/certificate', [EmployeeTrainingController::class, 'downloadCertificate'])->name('my_trainings.certificate');
    Route::resource('my_trainings', EmployeeTrainingController::class);
    Route::resource('my_leaves', EmployeeLeaveRequestController::class);
    Route::resource('my_payslips', EmployeePayslipController::class);
    Route::resource('my_performance', EmployeePerformanceController::class);
    Route::prefix('my_performance')->group(function() {
        Route::post('/store/ipcr_entry', [EmployeePerformanceController::class, 'storeIpcrEntry'])->name('my_performance.storeIPCREntry');
    });
    Route::resource('my_attendances', EmployeeAttendanceController::class);
});
