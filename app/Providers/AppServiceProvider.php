<?php

namespace App\Providers;

use App\Models\PerformanceCycle;
use App\Observers\PerformanceCycleObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Salary;
use App\Models\Position;
use App\Observers\AttendanceObserver;
use App\Observers\EmployeeObserver;
use App\Observers\LeaveRequestObserver;
use App\Observers\SalaryObserver;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Attendance::observe(AttendanceObserver::class);
        Employee::observe(EmployeeObserver::class);
        LeaveRequest::observe(LeaveRequestObserver::class);
        Salary::observe(SalaryObserver::class);
        PerformanceCycle::observe(PerformanceCycleObserver::class);

        // page title
        View::composer('layouts.app', function ($view) {
            $route = Route::currentRouteName();
            $title = match(true) {
                str_contains($route, 'login') => __('common.app_login'),
                str_contains($route, 'register') => __('common.app_register'),
                str_contains($route, 'password') => __('common.app_password'),
                str_contains($route, 'verify') => __('common.app_verify'),
                str_contains($route, 'verification') => __('common.app_verify'),
                str_contains($route, 'home') => __('common.app_dashboard'),
                str_contains($route, 'recruitment') => __('Recruitment'),
                str_contains($route, 'employee') => __('employee.title'),
                str_contains($route, 'schedule') => __('schedule.title'),
                str_contains($route, 'salaries') => __('salary.title'),
                str_contains($route, 'position') => __('position.title'),
                str_contains($route, 'department') => __('department.title'),
                str_contains($route, 'training') => __('training.title'),
                str_contains($route, 'attendance') => __('attendance.title'),
                str_contains($route, 'qr') => __('qr_code.title'),
                str_contains($route, 'employee_attendances') => __('employee_attendance.title'),
                str_contains($route, 'leave_requests') => __('leave_request.title'),
                str_contains($route, 'leave') => __('leave_type.title'),
                str_contains($route, 'deduction') => __('deduction.title'),
                str_contains($route, 'employee_compensations') => __('employee_deduction.title'),
                str_contains($route, 'performance') => __('Performance Management'),
                str_contains($route, 'payroll') => __('payroll.title'),
                str_contains($route, 'reports') => __('Reports'),
                str_contains($route, 'audit') => __('Audit Trail'),
                str_contains($route, 'profile') => __('profile.title'),
                str_contains($route, 'holiday') => __('holiday.title'),
                default => null
            };
            $view->with('pageTitle', $title);
        });

        // page header for admin
        View::composer('layouts.admin', function ($view) {
            $route = Route::currentRouteName();
            $header = match(true) {
                str_contains($route, 'employee_compensations') => __('employee_deduction.title'),
                str_contains($route, 'performance') => __('Performance Management'),
                str_contains($route, 'recruitment') => __('Recruitment'),
                str_contains($route, 'leave_requests') => __('common.app_leave'),
                str_contains($route, 'qr') => __('qr_code.title'),
                str_contains($route, 'department') => __('department.title'),
                str_contains($route, 'position') => __('position.title'),
                str_contains($route, 'employee') => __('common.app_personnel'),
                str_contains($route, 'training') => __('common.app_training'),
                str_contains($route, 'schedule') => __('schedule.title'),
                str_contains($route, 'attendance') => __('common.app_attendance'),
                str_contains($route, 'leave') => __('common.app_leave'),
                str_contains($route, 'salaries') => __('salary.title'),
                str_contains($route, 'deduction') => __('deduction.title'),
                str_contains($route, 'payroll') => __('common.app_payroll'),
                str_contains($route, 'holiday') => __('holiday.title'),
                default => null
            };
            $view->with('pageHeader', $header);
        });

        // page header for employee
        View::composer('layouts.employee', function ($view) {
            $route = Route::currentRouteName();
            $header = match(true) {
                str_contains($route, 'employee_attendances') => __('employee_attendance.title'),
                str_contains($route, 'employee_compensations') => __('employee_deduction.title'),
                str_contains($route, 'leave_requests') => __('common.app_leave'),
                str_contains($route, 'qr') => __('qr_code.title'),
                str_contains($route, 'department') => __('department.title'),
                str_contains($route, 'position') => __('position.title'),
                str_contains($route, 'training') => __('training.title'),
                str_contains($route, 'employee') => __('employee.title'),
                str_contains($route, 'schedule') => __('schedule.title'),
                str_contains($route, 'attendance') => __('attendance.title'),
                str_contains($route, 'training') => __('training.title'),
                str_contains($route, 'leave') => __('leave_type.title'),
                str_contains($route, 'salaries') => __('salary.title'),
                str_contains($route, 'deduction') => __('deduction.title'),
                str_contains($route, 'payroll') => __('payroll.title'),
                str_contains($route, 'holiday') => __('holiday.title'),
                default => null
            };
            $view->with('pageHeader', $header);
        });

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super-Admin')) {
                return true;
            }
        });
    }
}
