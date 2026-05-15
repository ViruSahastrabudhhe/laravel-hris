<?php

namespace App\Http\Controllers\Admin\Payroll;

use App\Enums\CompensationType;
use App\Http\Requests\Payroll\StorePayPeriodRequest;
use App\Models\Department;
use App\Models\PayPeriod;
use App\Models\PayrollRecord;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\BulkStorePayrollRecordRequest;
use App\Http\Requests\Payroll\StorePayrollRecordRequest;
use App\Http\Requests\Payroll\UpdatePayrollRecordRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;

class PayrollRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = PayPeriod::all();
        $records = PayrollRecord::with(['items', 'employee.department', 'employee.position', 'employee.salary'])
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();
        $employees = Employee::paginate(25);
        $departments = Department::get();

        return view('admin.payroll.index', compact(
            'periods',
            'records',
            'employees',
            'departments',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function storeRecord(StorePayrollRecordRequest $request) {
        $data = $request->validated();

        $month = $data['month'];
        $year  = $data['year'];
        $employee = Employee::find($data['employee_id']);

        $record = PayrollRecord::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'month' => $month, 'year' => $year],
            [
                'pay_period_id' => $data['pay_period_id'],
                'total_earnings' => $data['total_earnings'],
                'total_deductions' => $data['total_deductions'],
                'net_pay' => $data['net_pay'],
                'status' => 'Processed',
            ]
        );

        $record->items()->delete();

        $items = [
            ['name' => 'Basic Pay',    'type' => 'Earning',   'amount' => $employee->salary->amount ?? 0],
            ['name' => 'Overtime Pay', 'type' => 'Earning',   'amount' => $employee->overtimePay($month, $year)],
            ['name' => 'Absent/Late',  'type' => 'Earning', 'amount' => $employee->absentDeductions($month, $year)],
        ];

        foreach ($employee->employeeCompensation as $item) {
            $items[] = [
                'name'   => $item->compensation->name,
                'type'   => $item->compensation->type === CompensationType::Earning->value ? 'Earning' : 'Deduction',
                'amount' => $item->amount,
            ];
        }

        $record->items()->createMany($items);

        return redirect()->route('payroll.index');
    }

    public function storePeriod(StorePayPeriodRequest $request) {
        $data = $request->validated();

        PayPeriod::create([
            ''
        ]);

        return redirect()->route('payroll.index')->with('success', 'Period successfully created');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bulkStoreRecord(BulkStorePayrollRecordRequest $request)
    {
        $data = $request->validated();
        $month = $request->integer('month');
        $year  = $request->integer('year');

        $employees = Employee::with([
            'salary',
            'employeeLeaveBalance',
            'employeeCompensation.compensation'
        ])->get();

        foreach ($employees as $employee) {
            $record = PayrollRecord::updateOrCreate(
                ['employee_id' => $employee->id, 'month' => $month, 'year' => $year],
                [
                    'pay_period_id'    => $data['pay_period_id'],
                    'status'           => 'Draft',
                    'total_earnings'   => $employee->grossPay($month, $year),
                    'total_deductions' => $employee->totalDeductions($month, $year),
                    'net_pay'          => $employee->netPay($month, $year),
                ]
            );

            $record->items()->delete();

            $items = [
                ['name' => 'Basic Pay',    'type' => 'Earning',   'amount' => $employee->salary->amount ?? 0],
                ['name' => 'Overtime Pay', 'type' => 'Earning',   'amount' => $employee->overtimePay($month, $year)],
                ['name' => 'Absent/Late',  'type' => 'Deduction', 'amount' => $employee->absentDeductions($month, $year)],
            ];

            foreach ($employee->employeeCompensation as $item) {
                $items[] = [
                    'name'   => $item->compensation->name,
                    'type'   => $item->compensation->type === CompensationType::Earning->value ? 'Earning' : 'Deduction',
                    'amount' => $item->amount,
                ];
            }

            $record->items()->createMany($items);
        }

        return redirect()->route('payroll.index');
//        return response()->json(['message' => 'Payroll processed successfully.']);
    }

    public function activatePeriod(PayPeriod $period) {
        $periods = PayPeriod::all();
        foreach ($periods as $period) {
            $period->update(['is_active' => false]);
        }

        $period->update(['is_active' => true]);

        return redirect()->route('payroll.index')->with('success', 'Period successfully activated.');
    }
    public function deactivatePeriod(PayPeriod $period) {
        PayPeriod::update(['is_active' => false]);

        return redirect()->route('payroll.index')->with('success', 'Period successfully activated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $employeeID)
    {
        $employee = Employee::findOrFail($employeeID);
        return view('admin.payroll.payslip', ['employee' => $employee]);
    }

    public function exportPayslip(int $employeeID)
    {
        $employee = Employee::findOrFail($employeeID);

        $attendances = Attendance::where('employee_id', $employee->id)
            ->betweenCurrentMonth()
            ->whereNull('deleted_at')
            ->orderBy('date')
            ->get();

        $absentLateAttendances = $attendances->whereIn('attendance_status', [
            AttendanceStatus::Absent->value,
            AttendanceStatus::Late->value,
        ]);

        $lateCount = $attendances->where('attendance_status', AttendanceStatus::Late->value)->count();
        $absentCount = $attendances->where('attendance_status', AttendanceStatus::Absent->value)->count();

        $pdf = Pdf::loadView('admin.payroll.pdf.payslip', compact(
            'employee', 'attendances', 'absentLateAttendances', 'lateCount', 'absentCount'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('payslip-' . $employee->first_name . '-' . $employee->last_name . '-' . config('app.carbon_month') . '.pdf');
    }

    public function exportPayroll()
    {
        $employees = Employee::paginate(25);
        $filename = 'payroll-' . config('app.carbon_month') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($employees) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['PAYROLL SUMMARY - ' . config('app.carbon_month')]);
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

    public function filter(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $start = $request->start_date;
        $end   = $request->end_date;

        $isCurrentMonth =
            $month === now()->month &&
            $year === now()->year;

        if ($isCurrentMonth && !$start && !$end) {

            $employees = Employee::with([
                'salary',
                'leaveBalance',
                'employeeDeduction',
                'department'
            ])->get();

            return response()->json([
                'source' => 'live',
                'employees' => $employees->map(fn($e) => [
                    'id'           => $e->id,
                    'name'         => $e->first_name . ' ' . $e->last_name,
                    'department'   => $e->department->name,
                    'gross_pay'    => number_format($e->grossPay(), 2),
                    'compensations'=> number_format($e->totalDeductions(), 2),
                    'net_pay'      => number_format($e->netPay(), 2),
                ]),
            ]);
        }

        $records = PayrollRecord::with([
            'employee.department',
            'items'
        ])
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            })
            ->when(!($start && $end), function ($q) use ($month, $year) {
                $q->where('month', $month)
                    ->where('year', $year);
            })
            ->get();

        if ($records->isEmpty()) {
            return response()->json([
                'source' => 'unprocessed',
                'employees' => []
            ]);
        }

        return response()->json([
            'source' => 'snapshot',
            'employees' => $records->map(fn($r) => [
                'id'            => $r->employee->id,
                'name'          => $r->employee->first_name . ' ' . $r->employee->last_name,
                'department'    => $r->employee->department->name,
                'gross_pay'     => number_format($r->total_earnings, 2),
                'compensations' => number_format($r->total_deductions, 2),
                'net_pay'       => number_format($r->net_pay, 2),
                'items'         => $r->items->groupBy('type')->map(fn($g) => $g->map(fn($i) => [
                    'name'   => $i->name,
                    'amount' => number_format($i->amount, 2),
                ])),
            ]),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollRecord $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayrollRecordRequest $request, PayrollRecord $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyRecord(PayrollRecord $payroll)
    {
        //
    }

    public function destroyPeriod(PayPeriod $period) {
        $period->delete();

        return redirect()->route('payroll.index')->with('success', 'Period successfully deleted.');
    }
}
