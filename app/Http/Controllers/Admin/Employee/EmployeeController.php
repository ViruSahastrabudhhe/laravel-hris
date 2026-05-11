<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Address;
use App\Models\Department;
use App\Models\WorkSchedule;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeCompensation;
use App\Models\Salary;
use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::paginate(25);
        $positions = Position::paginate(25);
        $departments = Department::paginate(25);
        $employmentTypes = EmploymentType::cases();
        $workSchedules = WorkSchedule::paginate(25);
        $salaryTypes = SalaryType::cases();

        return view('admin.personnel.index', compact('employees', 'positions', 'departments', 'employmentTypes', 'workSchedules', 'salaryTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::paginate(25);
        $departments = Department::paginate(25);
        $employmentTypes = EmploymentType::cases();
        $workSchedules = WorkSchedule::paginate(25);

        return view('admin.personnel.create', ['positions' => $positions, 'departments' => $departments, 'workSchedules' => $workSchedules, 'employmentTypes' => $employmentTypes, 'salaryTypes' => SalaryType::cases()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        // creates address, employee, emp work schedule, and emp leave balance at once
        $employeeAccount = new User;
        $employeeAccount->name = $data['first_name'] . ' ' . $data['last_name'];
        $employeeAccount->email = $data['email'];
        $employeeAccount->password = Hash::make($data['password']);
        $employeeAccount->save();

        $employeeAccount->assignRole('employee');

        $employee = Employee::createQuietly([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'employment_type' => $data['employment_type'],
            'is_active' => $data['is_active'],
            'position_id' => $data['position_id'],
            'department_id' => $data['department_id'],
            'user_id' => $employeeAccount->id,
        ]);

        $employeeSalary = Salary::create(array_merge($data['salary'], [
            'employee_id' => $employee->id,
        ]));

        $employeeObserver = new \App\Observers\EmployeeObserver();
        $employeeObserver->created($employee);

        $address = Address::create($data['address']);
        $address->employee_id = $employee->id;
        $address->save();

        $employeeWorkSchedule = new EmployeeWorkSchedule;
        $employeeWorkSchedule->employee_id = $employee->id;
        $employeeWorkSchedule->work_schedule_id = $data['work_schedule_id'];
        $employeeWorkSchedule->save();

        event(new Registered($employeeAccount));

        return redirect()->route('employees.index')->with('success', __('employee.success_creating'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($file); // skip header row

        $errors = [];
        $row = 2;

        while (($data = fgetcsv($file)) !== false) {
            $record = array_combine($header, $data);

            if (User::where('email', $record['email'])->exists() || Employee::where('email', $record['email'])->exists()) {
                $errors[] = "Row {$row}: Email {$record['email']} already exists.";
                $row++;
                continue;
            }

            DB::beginTransaction();
            try {
                $employeeAccount = User::create([
                    'name'     => $record['first_name'] . ' ' . $record['last_name'],
                    'email'    => $record['email'],
                    'password' => Hash::make($record['password']),
                ]);

                $employeeAccount->assignRole('employee');

                $employee = Employee::createQuietly([
                    'first_name'      => $record['first_name'],
                    'last_name'       => $record['last_name'],
                    'gender'          => $record['gender'],
                    'email'           => $record['email'],
                    'date_of_birth'   => $record['date_of_birth'],
                    'phone_number'    => $record['phone_number'],
                    'employment_type' => $record['employment_type'],
                    'is_active'       => $record['is_active'],
                    'position_id'     => $record['position_id'],
                    'department_id'   => $record['department_id'],
                    'user_id'         => $employeeAccount->id,
                ]);

                Salary::create([
                    'employee_id'  => $employee->id,
                    'salary_type'  => $record['salary_type'],
                    'amount'       => $record['amount'],
                    'salary_grade' => $record['salary_grade'],
                    'step'         => $record['step'],
                ]);

                (new \App\Observers\EmployeeObserver())->created($employee);

                Address::create([
                    'employee_id' => $employee->id,
                    'country'     => $record['country'],
                    'zip_code'    => $record['zip_code'],
                    'city'        => $record['city'],
                    'address'     => $record['address'],
                    'province'    => $record['province'],
                ]);

                EmployeeWorkSchedule::create([
                    'employee_id'      => $employee->id,
                    'work_schedule_id' => $record['work_schedule_id'],
                ]);

                event(new Registered($employeeAccount));

                DB::commit();
                $row++;
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = "Row {$row}: Failed to import (check foreign keys). Error: " . $e->getMessage();
                $row++;
            }
        }

        fclose($file);

        if (!empty($errors)) {
            return redirect()->route('employees.index')
                ->with('warning', implode('<br>', $errors));
        }

        return redirect()->route('employees.index')->with('success', __('employee.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $address = Address::where('employee_id', $employee->id)->first();
        $sickLeave = EmployeeLeaveBalance::where('employee_id', $employee->id)->where('type', 'Sick')?->first()?->amount;
        $vacationLeave = EmployeeLeaveBalance::where('employee_id', $employee->id)->where('type', 'Vacation')?->first()?->amount;

        return view('admin.personnel.show', compact('address', 'employee', 'sickLeave', 'vacationLeave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $positions = Position::paginate(25);
        $departments = Department::paginate(25);
        $workSchedules = WorkSchedule::paginate(25);
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

        $employee->update($data);

        $employee->address()->update($data['address']);

        if (!empty($data['salary'])) {
            $employee->salary()->updateOrCreate(
                ['employee_id' => $employee->id],
                array_merge($data['salary'], ['user_id' => auth()->user()->id])
            );
        }

        return redirect()->route('employees.index')->with('success', __('employee.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        $employee->is_active = false;
        $employee->save();

        return redirect()->route('employees.index')->with('success', __('employee.success_deleting'));
    }

    public function restore($employeeId)
    {
        $employee = Employee::onlyTrashed()->findOrFail($employeeId);
        $employee->restore();
        $employee->is_active = true;
        $employee->save();

        return redirect()->route('employees.archive')->with('success', __('employee.success_restoring'));
    }

    public function activate($employeeId) {
        $employee = Employee::findOrFail($employeeId);
        $employee->is_active = true;
        $employee->save();

        return redirect()->route('employees.index')->with('success', __('employee.success_activating'));
    }

    public function deactivate($employeeId) {
        $employee = Employee::findOrFail($employeeId);
        $employee->is_active = false;
        $employee->save();

        return redirect()->route('employees.index')->with('success', __('employee.success_deactivating'));
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists()
            || Employee::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function archive() {
        $employees = Employee::onlyTrashed()->get();
        $positions = Position::paginate(25);
        $departments = Department::paginate(25);

        return view('admin.personnel.archive', compact('employees', 'positions', 'departments'));
    }
}
