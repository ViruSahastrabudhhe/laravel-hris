<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\Salary;
use App\Models\Position;
use App\Observers\AttendanceObserver;
use App\Observers\EmployeeObserver;
use App\Observers\EmployeeLeaveObserver;
use App\Observers\SalaryObserver;
use App\Observers\PositionObserver;
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
        EmployeeLeave::observe(EmployeeLeaveObserver::class);
        Salary::observe(SalaryObserver::class);
        Position::observe(PositionObserver::class);
        
        View::composer('layouts.employee', function ($view) {
            $route = Route::currentRouteName();
            $header = match(true) {
                str_contains($route, 'employee_attendances') => __('employee_attendance.title'),
                str_contains($route, 'employee_deductions') => __('employee_deduction.title'),
                str_contains($route, 'employee_leaves') => __('employee_leave.title'),
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

        View::composer('layouts.app', function ($view) {
            $route = Route::currentRouteName();
            $title = match(true) {
                str_contains($route, 'home') => __('common.app_dashboard'),
                str_contains($route, 'verification') => __('common.app_verify'),
                str_contains($route, 'login') => __('common.app_login'),
                str_contains($route, 'register') => __('common.app_register'),
                str_contains($route, 'password') => __('common.app_password'),
                str_contains($route, 'verify') => __('common.app_verify'),
                str_contains($route, 'employee_attendances') => __('employee_attendance.title'),
                str_contains($route, 'employee_deductions') => __('employee_deduction.title'),
                str_contains($route, 'employee_leaves') => __('employee_leave.title'),
                str_contains($route, 'department') => __('department.title'),
                str_contains($route, 'qr') => __('qr_code.title'),
                str_contains($route, 'training') => __('training.title'),
                str_contains($route, 'position') => __('position.title'),
                str_contains($route, 'employee') => __('employee.title'),
                str_contains($route, 'salaries') => __('salary.title'),
                str_contains($route, 'schedule') => __('schedule.title'),
                str_contains($route, 'attendance') => __('attendance.title'),
                str_contains($route, 'leave') => __('leave_type.title'),
                str_contains($route, 'deduction') => __('deduction.title'),
                str_contains($route, 'payroll') => __('payroll.title'),
                str_contains($route, 'holiday') => __('holiday.title'),
                default => null
            };
            $view->with('pageTitle', $title);
        });

        View::composer('layouts.admin', function ($view) {
            $route = Route::currentRouteName();
            $header = match(true) {
                str_contains($route, 'employee_deductions') => __('employee_deduction.title'),
                str_contains($route, 'employee_leaves') => __('common.app_leave'),
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

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super-Admin')) {
                return true;
            }
        });
    }
}
