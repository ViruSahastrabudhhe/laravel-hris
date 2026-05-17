<?php

namespace App\Http\Controllers\Admin\Payroll;

use App\Enums\CompensationCategory;
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
use Illuminate\Support\Facades\Log;

class PayrollRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $periods = PayPeriod::get();
        $records = PayrollRecord::with(['items', 'employee.department', 'employee.position', 'employee.salary'])
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();
        $departments = Department::get();

        $payrollStats = [
            'gross_pay'         => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->sum('total_earnings'),
            'net_pay'           => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->sum('net_pay'),
            'total_earnings'    => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->sum('total_earnings'),
            'total_deductions'  => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->sum('total_deductions'),
            'pending_records'   => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->where('status', 'Draft')->count(),
            'total_personnel'   => $records->where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->count(),
        ];

        return view('admin.payroll.index', compact(
            'periods',
            'payrollStats',
            'records',
            'departments',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
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
            ['name' => 'Basic Pay',    'category' => CompensationCategory::Earning->value,   'amount' => $employee->salary->amount ?? 0],
            ['name' => 'Overtime Pay', 'category' => CompensationCategory::Earning->value,   'amount' => $employee->overtimePay($month, $year)],
            ['name' => 'Absent/Late',  'category' => CompensationCategory::Deduction->value, 'amount' => $employee->absentDeductions($month, $year)],
        ];

        foreach ($employee->employeeCompensation as $item) {
            $items[] = [
                'name'   => $item->compensation->name,
                'category'   => $item->compensation->category === CompensationCategory::Earning->value ? 'Earning' : 'Deduction',
                'amount' => $item->amount,
            ];
        }

        $record->items()->createMany($items);

        return redirect()->route('payroll.index');
    }

    public function storePeriod(StorePayPeriodRequest $request) {
        $data = $request->validated();

        $active = $request->input('is_active');
        $message = 'Periods successfully created.';

        $targetDate = Carbon::createFromDate($data['year'], $data['month'], 1);

        $firstHalf = PayPeriod::withTrashed()->firstOrNew([
            'start_date' => $targetDate->copy()->startOfMonth()->toDateString(), 
            'end_date'   => $targetDate->copy()->day(15)->toDateString(),
            'month'      => $data['month'],
            'year'       => $data['year'],
        ]);

        $secondHalf = PayPeriod::withTrashed()->firstOrNew([
            'start_date' => $targetDate->copy()->day(16)->toDateString(), 
            'end_date'   => $targetDate->copy()->endOfMonth()->toDateString(),
            'month'      => $data['month'],
            'year'       => $data['year'],
        ]);

        if (($firstHalf->exists && $secondHalf->exists) && ($firstHalf->deleted_at == null && $secondHalf->deleted_at == null)) {
            return redirect()->route('payroll.index')->withInput()->withErrors(['period' => 'Pay periods for the selected month and year already exist.']);
        }

        $periods = PayPeriod::get();
        foreach ($periods as $p) {
            $p->update(['is_active' => false]);
        }

        if ($firstHalf->exists && $secondHalf->exists) {
            $firstHalf->restore();
            $secondHalf->restore();
            $message = 'Periods successfully restored.';
        }


        if ($firstHalf->exists && $firstHalf->deleted_at != null) {
            $firstHalf->restore();
            $message = 'Period successfully restored.';
        }
            
        if ($secondHalf->exists && $secondHalf->deleted_at != null) {
            $secondHalf->restore();
            $message = 'Period successfully restored.';
        }

        $firstHalfStartDate = now()->startOfMonth()->format('d');
        $firstHalfEndDate = now()->day(15)->format('d');
        
        $secondHalfStartDate = now()->day(16)->format('d');
        $secondHalfEndDate = now()->endOfMonth()->format('d');

        $firstHalfName = $firstHalfStartDate . '-' 
                . $firstHalfEndDate . ' '
                . Carbon::createFromDate(null, $data['month'], 1)->format('F') . ' '
                . $data['year'];
        $secondHalfName = $secondHalfStartDate . '-' 
                . $secondHalfEndDate . ' '
                . Carbon::createFromDate(null, $data['month'], 1)->format('F') . ' '
                . $data['year'];

        $firstHalf->name = $firstHalfName;
        if ($active=='firstHalf') {
            $firstHalf->is_active = true;
        }
        $firstHalf->save();

        $secondHalf->name = $secondHalfName;
        if ($active=='secondHalf') {
            $secondHalf->is_active = true;
        }
        $secondHalf->save();

        return redirect()->route('payroll.index')->with('success', $message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bulkStoreRecord(BulkStorePayrollRecordRequest $request) {
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
                ['name' => 'Basic Pay',    'category' => CompensationCategory::Earning->value,   'amount' => $employee->salary->amount ?? 0],
                ['name' => 'Overtime Pay', 'category' => CompensationCategory::Earning->value,   'amount' => $employee->overtimePay($month, $year)],
                ['name' => 'Absent/Late',  'category' => CompensationCategory::Deduction->value, 'amount' => $employee->absentDeductions($month, $year)],
            ];

            foreach ($employee->employeeCompensation as $item) {
                $items[] = [
                    'name'   => $item->compensation->name,
                    'type'   => $item->compensation->type === CompensationCategory::Earning->value ? 'Earning' : 'Deduction',
                    'amount' => $item->amount,
                ];
            }

            $record->items()->createMany($items);
        }

        return redirect()->route('payroll.index');
    }

    public function quickStorePeriod(Request $request) {
        $firstHalfStartDate = now()->startOfMonth()->format('d');
        $firstHalfEndDate = now()->day(15)->format('d');
        
        $secondHalfStartDate = now()->day(16)->format('d');
        $secondHalfEndDate = now()->endOfMonth()->format('d');

        $firstHalfName = $firstHalfStartDate . '-' 
                . $firstHalfEndDate . ' '
                . Carbon::createFromDate(null, now()->month, 1)->format('F') . ' '
                . now()->year;
        $secondHalfName = $secondHalfStartDate . '-' 
                . $secondHalfEndDate . ' '
                . Carbon::createFromDate(null, now()->month, 1)->format('F') . ' '
                . now()->year;

        $targetDate = Carbon::createFromDate(now()->year, now()->month, 1);

        $firstHalf = PayPeriod::firstOrNew([
            'start_date' => $targetDate->copy()->startOfMonth()->toDateString(), 
            'end_date'   => $targetDate->copy()->day(15)->toDateString(),
            'month'      => now()->month,
            'year'       => now()->year,
        ]);
        $firstHalf->name = $firstHalfName;
        $firstHalf->save();

        $secondHalf = PayPeriod::firstOrNew([
            'start_date' => $targetDate->copy()->day(16)->toDateString(), 
            'end_date'   => $targetDate->copy()->endOfMonth()->toDateString(),
            'month'      => now()->month,
            'year'       => now()->year,
        ]);
        $secondHalf->name = $secondHalfName;
        $secondHalf->save();

        return response()->json(['success' => true, 'message' => 'Successfully created pay periods!']);
    }

    public function activatePeriod(PayPeriod $period) {
        $periods = PayPeriod::get();
        foreach ($periods as $p) {
            $p->update(['is_active' => false]);
        }

        $period->update(['is_active' => true]);

        return redirect()->route('payroll.index')->with('success', 'Period successfully activated.');
    }
    public function deactivatePeriod(PayPeriod $period) {
        $period->update(['is_active' => false]);

        return redirect()->route('payroll.index')->with('success', 'Period successfully deactivated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $employeeID) {
        $employee = Employee::findOrFail($employeeID);
        return view('admin.payroll.payslip', ['employee' => $employee]);
    }

    public function exportPayslip(int $employeeID) {
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

    public function exportPayroll() {
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

    public function filter(Request $request) {
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

    public function processRecord(PayrollRecord $payroll) {
        $payroll->update(['status' => 'Processed']);

        return redirect()->route('payroll.index')->with('success', 'Payroll record successfully processed.');
    }

    public function bulkProcessRecord(Request $request) {
        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer',
        ]);
        
        $month = $request->integer('month', $data['month']);
        $year  = $request->integer('year', $data['year']);

        PayrollRecord::where('month', $month)
            ->where('year', $year)
            ->update(['status' => 'Processed']);

        return redirect()->route('payroll.index')->with('success', 'Payroll records successfully processed.');
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
