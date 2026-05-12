<?php

namespace App\Http\Controllers\Employee\Payslip;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollRecord;
use Illuminate\Http\Request;

class EmployeePayslipController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where('user_id', auth()->id())->first();

        $startMonth = $request->input('start_month'); // format: YYYY-MM
        $endMonth   = $request->input('end_month');

        if (!$employee) {
            return view('employee.payslip.index', [
                'payslips'      => collect(),
                'latestPayslip' => null,
                'totalPayslips' => 0,
                'startMonth'    => $startMonth,
                'endMonth'      => $endMonth,
            ]);
        }

        $query = PayrollRecord::where('employee_id', $employee->id);

        if ($startMonth) {
            [$sy, $sm] = explode('-', $startMonth);
            $query->where(function($q) use ($sy, $sm) {
                $q->where('year', '>', $sy)
                  ->orWhere(function($q2) use ($sy, $sm) {
                      $q2->where('year', $sy)->where('month', '>=', (int)$sm);
                  });
            });
        }

        if ($endMonth) {
            [$ey, $em] = explode('-', $endMonth);
            $query->where(function($q) use ($ey, $em) {
                $q->where('year', '<', $ey)
                  ->orWhere(function($q2) use ($ey, $em) {
                      $q2->where('year', $ey)->where('month', '<=', (int)$em);
                  });
            });
        }

        $payslips      = $query->latest()->get();
        $latestPayslip = PayrollRecord::where('employee_id', $employee->id)->latest()->first();
        $totalPayslips = PayrollRecord::where('employee_id', $employee->id)->count();

        return view('employee.payslip.index', compact(
            'payslips', 'latestPayslip', 'totalPayslips', 'startMonth', 'endMonth'
        ));
    }

    public function show(PayrollRecord $my_payslip)
    {
        $my_payslip->load('items');

        return response()->json([
            'period'           => $my_payslip->month . '/' . $my_payslip->year,
            'earnings'         => $my_payslip->earnings()->get(),
            'deductions'       => $my_payslip->deductions()->get(),
            'total_earnings'   => $my_payslip->total_earnings,
            'total_deductions' => $my_payslip->total_deductions,
            'net_pay'          => $my_payslip->net_pay,
            'status'           => $my_payslip->status,
        ]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
