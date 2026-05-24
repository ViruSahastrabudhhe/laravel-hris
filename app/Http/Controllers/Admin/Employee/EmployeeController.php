<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Address;
use App\Models\Department;
use App\Models\WorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use App\Services\Employee\EmployeeService;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    public function getEmployees() {
        return response()->json($this->employeeService->getAll());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = $this->employeeService->getAll();
        $positions = Cache::get('position:all');
        $departments = Cache::get('department:all');
        $workSchedules = Cache::get('workSchedule:all');

        $employeeStats = $this->employeeService->getEmployeeStats();
        $positionStats = Cache::get('position_stat:all');
        $departmentStats = Cache::get('department_stat:all');

        $employmentTypes = EmploymentType::cases();
        $salaryTypes = SalaryType::cases();

        return view('admin.personnel.index', compact(
            'employees',
            'employeeStats',
            'positions',
            'positionStats',
            'departments',
            'departmentStats',
            'employmentTypes',
            'workSchedules',
            'salaryTypes'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('employees.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request) {
        $data = $request->validated();

        $this->employeeService->saveEmployeeData($data);

        return redirect()->route('employees.index')->with('success', __('employee.success_creating'));
    }

    public function bulkStore(Request $request) {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);
        $csv = $request->file('csv_file');

        $errors = $this->employeeService->bulkSaveEmployeeData($csv);
        if (!empty($errors)) {
            return redirect()->route('employees.index')->withErrors(['errors' => $errors]);
        }

        return redirect()->route('employees.index')->with('success', __('employee.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee) {
        $employee = $this->employeeService->getEmployee($employee->id);
        $employees = $this->employeeService->getAll();
        $positions = Cache::get('position:all');
        $departments = Cache::get('department:all');
        $workSchedules = Cache::get('workSchedule:all');
        $employmentTypes = EmploymentType::cases();
        $salaryTypes = SalaryType::cases();

        return view('admin.personnel.show', compact(
            'employee',
            'employees',
            'positions',
            'employmentTypes',
            'departments',
            'workSchedules',
            'salaryTypes',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee) {
        $positions = Cache::get('positions');
        $departments = Cache::get('departments');
        $workSchedules = Cache::get('workSchedules');
        $employmentTypes = EmploymentType::cases();

        return view('admin.personnel.edit',
            [
                'employee' => $employee,
                'positions' => $positions,
                'departments' => $departments,
                'workSchedules' => $workSchedules,
                'employmentTypes' => $employmentTypes,
                'salaryTypes' => SalaryType::cases(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        $this->employeeService->updateEmployeeData($employee->id, $data);

        return redirect()->route('employees.index')->with('success', __('employee.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->employeeService->deleteEmployee($employee->id);

        return redirect()->route('employees.index')->with('success', __('employee.success_deleting'));
    }

    public function restore(int $employeeId) {
        $this->employeeService->restoreEmployee($employeeId);

        return redirect()->route('employees.archive')->with('success', __('employee.success_restoring'));
    }

    public function activate(int $employeeId) {
        $this->employeeService->activateEmployee($employeeId);

        return redirect()->route('employees.index')->with('success', __('employee.success_activating'));
    }

    public function deactivate(int $employeeId) {
        $this->employeeService->deactivateEmployee($employeeId);

        return redirect()->route('employees.index')->with('success', __('employee.success_deactivating'));
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists()
            || Employee::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function archive() {
        $employees = $this->employeeService->getAllOnlyTrashed();
        $positions = Cache::get('positions');
        $departments = Cache::get('departments');

        return view('admin.personnel.archive', compact('employees', 'positions', 'departments'));
    }
}
