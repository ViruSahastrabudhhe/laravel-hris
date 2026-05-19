<?php

namespace App\Models;

use App\Models\Department;
use App\Models\EmployeeCompensation;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeTraining;
use App\Models\LeaveRequest;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeWorkSchedule;
use Carbon\Carbon;
use App\Enums\EmploymentType;
use App\Enums\CompensationCategory;
use App\Enums\AttendanceStatus;
use App\Models\Scopes\EmployeeScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

#[ScopedBy([EmployeeScope::class])]
class Employee extends Model implements Auditable
{
    use HasFactory, SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $table = 'employees';

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'gender', 'email', 'date_of_birth',
        'phone_number', 'employment_type', 'is_active',
        'position_id', 'department_id', 'user_id',
    ];

    public function department() { return $this->hasOne(Department::class, 'id', 'department_id'); }
    public function position() { return $this->hasOne(Position::class, 'id', 'position_id'); }
    public function salary() { return $this->hasOne(Salary::class); }
    public function employeeAttendance() { return $this->hasMany(EmployeeAttendance::class); }
    public function attendance() { return $this->hasMany(Attendance::class); }
    public function attendanceScanLog() { return $this->hasMany(AttendanceScanLog::class); }
    public function employeeCompensation() { return $this->hasMany(EmployeeCompensation::class); }
    public function employeeTraining() { return $this->hasMany(EmployeeTraining::class); }
    public function address() { return $this->hasOne(Address::class); }
    public function leaves() { return $this->hasMany(LeaveRequest::class); }
    public function employeeLeaveBalance() { return $this->hasMany(EmployeeLeaveBalance::class); }
    public function employeeWorkSchedule() { return $this->hasOne(EmployeeWorkSchedule::class); }
    public function qrAttendanceScans() { return $this->hasMany(QrAttendanceScan::class); }
    public function payrollRecords() { return $this->hasMany(PayrollRecord::class); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    public function isJobOrder() {
        return $this->employment_type == EmploymentType::JobOrder->value;
    }

    private function attendanceQuery(?int $month = null, ?int $year = null) {
        $query = Attendance::where('attendances.employee_id', $this->id)
            ->whereNull('attendances.deleted_at');

        return ($month && $year)
            ? $query->forPeriod($month, $year)
            : $query->betweenCurrentMonth();
    }

    public function hoursWorked(?int $month = null, ?int $year = null) {
        $total_minutes = $this->attendanceQuery($month, $year)->sum('total_minutes');
        return round($total_minutes / 60, 2);
    }

    public function daysWorked(?int $month = null, ?int $year = null) {
        return $this->attendanceQuery($month, $year)->count();
    }

    public function overtimeWorked(?int $month = null, ?int $year = null) {
        $overtime_minutes = $this->attendanceQuery($month, $year)->sum('overtime_minutes');
        return round($overtime_minutes / 60, 2);
    }

    public function totalHoursWorked(?int $month = null, ?int $year = null) {
        return $this->hoursWorked($month, $year) + $this->overtimeWorked($month, $year);
    }

    public function hourlyRate() {
        $amount = $this->salary->amount ?? 0;
        return ($amount * 12) / (261 * 8);
    }

    public function dailyRate() {
        $amount = $this->salary->amount ?? 0;
        return ($amount * 12) / 261;
    }

    public function basicPay() {
        return $this->salary->amount ?? 0;
    }

    public function amountAccruedForPeriod() {
        return ($this->salary->amount/2) ?? 0;
    }

    public function overtimePay(int $payPeriodId): float {
        $payPeriod = PayPeriod::find($payPeriodId);

        return round(
            $this->hourlyRate() * 1.25 * $this->overtimeWorked($payPeriod->month, $payPeriod->year),
            2
        );
    }

    public function payrollTotalEarnings(int $payPeriodId): float {
        $basicPay = $this->amountAccruedForPeriod();
        $overtime = $this->overtimePay($payPeriodId);
        $earnings = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('employee_compensations.pay_period_id', $payPeriodId)
            ->where('compensations.category', CompensationCategory::Earning->value)
            ->whereNull('employee_compensations.deleted_at')
            ->sum('amount');

        return round($basicPay + $overtime + $earnings, 2);
    }

    public function payrollTotalDeductions(int $payPeriodId): float {
        $lateDeductions = $this->payrollLateDeductions($payPeriodId);
        $absentDeductions = $this->payrollAbsentDeductions($payPeriodId);
        $employeeDeductions = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('employee_compensations.pay_period_id', $payPeriodId)
            ->where('compensations.category', CompensationCategory::Deduction->value)
            ->whereNull('employee_compensations.deleted_at')
            ->sum('amount');

        return round($lateDeductions + $absentDeductions + $employeeDeductions, 2);
    }

    public function totalEarnings(int $payPeriodId): float {
        return round(
            EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
                ->where('employee_compensations.employee_id', $this->id)
                ->where('employee_compensations.pay_period_id', $payPeriodId)
                ->where('compensations.category', CompensationCategory::Earning->value)
                ->sum('employee_compensations.amount'),
            2
        );
    }

    public function totalDeductions(int $payPeriodId): float
    {
        return round(
            EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
                ->where('employee_compensations.employee_id', $this->id)
                ->where('employee_compensations.pay_period_id', $payPeriodId)
                ->where('compensations.category', CompensationCategory::Deduction->value)
                ->sum('employee_compensations.amount'),
            2
        );
    }

    public function netPay(int $payPeriodId): float {
        return round($this->grossPay($payPeriodId) - $this->totalDeductions($payPeriodId), 2);
    }
    public function amountPaid(int $payPeriodId): float {
        return round($this->payrollTotalEarnings($payPeriodId) - $this->payrollTotalDeductions($payPeriodId), 2);
    }

    public function daysLate(?int $month = null, ?int $year = null) {
        return $this->attendanceQuery($month, $year)
            ->where('attendance_status', AttendanceStatus::Late->value)
            ->count();
    }

    public function daysAbsent(?int $month = null, ?int $year = null) {
        return $this->attendanceQuery($month, $year)
            ->where('attendance_status', AttendanceStatus::Absent->value)
            ->count();
    }

    public function payrollLateDeductions(?int $month = null, ?int $year = null): float {
        $lates = $this->daysLate($month, $year);

        return round(($lates * ($this->dailyRate() * 0.5)), 2);
    }
    public function payrollAbsentDeductions(?int $month = null, ?int $year = null): float {
        $absences = $this->daysLate($month, $year);

        return round(($absences * $this->dailyRate()), 2);
    }

    public function absentDeductions(?int $month = null, ?int $year = null): float {
        $leaveBalance = $this->leaveBalance;

        if (!$this->isJobOrder()) {
            if (!$leaveBalance || $leaveBalance->leave_balance > 0) {
                return 0;
            }
        }

        $absences = $this->daysAbsent($month, $year);
        $lates    = $this->daysLate($month, $year);

        return round(($absences * $this->dailyRate()) + ($lates * ($this->dailyRate() * 0.5)), 2);
    }

    public function gsisContribution(): float {
        if ($this->isJobOrder()) return 0;
        $gsis = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('compensations.id', 1)->first();
        return $gsis ? round($gsis->amount, 2) : 0;
    }

    public function philHealthContribution(): float {
        if ($this->isJobOrder()) return 0;
        $philHealth = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('compensations.id', 2)->first();
        return $philHealth ? round($philHealth->amount, 2) : 0;
    }

    public function pagIbigContribution(): float {
        if ($this->isJobOrder()) return 0;
        $pagibig = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('compensations.id', 3)->first();
        return $pagibig ? round($pagibig->amount, 2) : 0;
    }

    public function optionalDeductions(): float {
        $optional = EmployeeCompensation::join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employee_compensations.employee_id', $this->id)
            ->where('compensations.is_mandatory', false)
            ->get();

        return round($optional->sum('amount'), 2);
    }

    private function resolvePayPeriodId(?int $month, ?int $year): int {
        return PayPeriod::where('month', $month)
            ->where('year', $year)
            ->value('id');
    }

    public function netTaxableIncome(?int $month = null, ?int $year = null) {
        $payPeriod = $this->resolvePayPeriodId($month, $year);
        return round($this->payrollTotalEarnings($payPeriod) - $this->gsisContribution() - $this->philHealthContribution() - $this->pagIbigContribution(), 2);
    }

    public function withholdingTax(?int $month = null, ?int $year = null) {
        $month = now()->month;
        $year = now()->year;

        $nti = $this->netTaxableIncome($month, $year);
        if ($nti < 20833) return 0;
        if ($nti <= 33332) return ($nti - 20833) * 0.15;
        if ($nti <= 66666) return (($nti - 33333) * 0.20) + 1875;
        if ($nti <= 166666) return (($nti - 66667) * 0.25) + 8541.80;
        if ($nti <= 666666) return (($nti - 166667) * 0.30) + 33541.80;
        return (($nti - 666667) * 0.35) + 183541.80;
    }
}
