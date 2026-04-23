<?php

namespace App\Http\Controllers\Payroll;

use App\Models\Payroll;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StorePayrollRequest;
use App\Http\Requests\Payroll\UpdatePayrollRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\AttendanceStatus;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::findAllWithUserID()->get();
        $currentMonth = Carbon::now();
        
        return view('payroll.index', ['employees' => $employees, 'calendar' => $currentMonth]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayrollRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $employeeID)
    {
        $employee = Employee::findAllWithUserID()->findOrFail($employeeID);
        return view('payroll.payslip', ['employee' => $employee]);
    }

    public function exportPayslip(int $employeeID)
    {
        $employee = Employee::findAllWithUserID()->findOrFail($employeeID);

        $attendances = Attendance::where('user_id', auth()->user()->id)
            ->where('employee_id', $employee->id)
            ->currentMonthBetween()
            ->whereNull('deleted_at')
            ->orderBy('date')
            ->get();

        $absentLateAttendances = $attendances->whereIn('attendance_status', [
            AttendanceStatus::Absent->value,
            AttendanceStatus::Late->value,
        ]);

        $lateCount = $attendances->where('attendance_status', AttendanceStatus::Late->value)->count();
        $absentCount = $attendances->where('attendance_status', AttendanceStatus::Absent->value)->count();

        $pdf = Pdf::loadView('payroll.pdf.payslip', compact(
            'employee', 'attendances', 'absentLateAttendances', 'lateCount', 'absentCount'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('payslip-' . $employee->first_name . '-' . $employee->last_name . '-' . config('app.month') . '.pdf');
    }

    public function exportPayroll()
    {
        $employees = Employee::findAllWithUserID()->get();
        $filename = 'payroll-' . config('app.month') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($employees) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['PAYROLL SUMMARY - ' . config('app.month')]);
            fputcsv($out, ['Generated', now()->format('F d, Y h:i A')]);
            fputcsv($out, []);

            fputcsv($out, ['Employee', 'Department', 'Gross Pay', 'GSIS', 'PhilHealth', 'Pag-Ibig', 'Tax', 'Optional Deductions', 'Absent/Late', 'Net Pay']);

            foreach ($employees as $e) {
                fputcsv($out, [
                    $e->first_name . ' ' . $e->last_name,
                    $e->department->name,
                    $e->grossPay(),
                    $e->gsisContribution(),
                    $e->philHealthContribution(),
                    $e->pagIbigContribution(),
                    $e->withholdingTax(),
                    $e->optionalDeductions(),
                    $e->absentDeductions(),
                    $e->netPay(),
                ]);
            }

            fputcsv($out, []);
            fputcsv($out, [
                'TOTALS', '',
                $employees->sum(fn($e) => $e->grossPay()),
                $employees->sum(fn($e) => $e->gsisContribution()),
                $employees->sum(fn($e) => $e->philHealthContribution()),
                $employees->sum(fn($e) => $e->pagIbigContribution()),
                $employees->sum(fn($e) => $e->withholdingTax()),
                $employees->sum(fn($e) => $e->optionalDeductions()),
                $employees->sum(fn($e) => $e->absentDeductions()),
                $employees->sum(fn($e) => $e->netPay()),
            ]);

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        //
    }
}
