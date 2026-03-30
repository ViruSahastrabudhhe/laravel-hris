<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

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
        View::composer('layouts.app', function ($view) {
            $route = Route::currentRouteName();
            $title = match(true) {
                str_contains($route, 'employee_attendances') => __('employee_attendance.title'),
                str_contains($route, 'employee_deductions') => __('employee_deduction.title'),
                str_contains($route, 'employee_leaves') => __('employee_leave.title'),
                str_contains($route, 'department') => __('department.title'),
                str_contains($route, 'position') => __('position.title'),
                str_contains($route, 'employee') => __('employee.title'),
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
    }
}
