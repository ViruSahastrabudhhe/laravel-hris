<?php

namespace App\Repositories\Employee;

use App\Enums\EmploymentType;
use App\Interfaces\RepositoryInterface;
use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\Address;
use App\Models\Salary;
use App\Models\User;
use App\Observers\EmployeeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeRepository implements RepositoryInterface
{
    protected $employee;

    public function __construct(Employee $employee) {
        $this->employee = $employee;
    }

    public function getAll(): ?Collection {
        return Cache::remember(
            "employee:all",
            now()->minutes(10),
            function () {
                return $this->employee->with('department', 'position')->get();
            }
        );
    }

    public function getAllOnlyTrashed(): ?Collection {
        return Cache::remember(
            "employee:all",
            now()->minutes(10),
            function () {
                return $this->employee->onlyTrashed()->get();
            }
        );
    }

    public function getById(int $id): ?Employee {
        return Cache::remember(
            "employee:{$id}",
            now()->addHour(),
            function () use ($id) {
                return $this->employee->with('address', 'employeeLeaveBalance', 'salary')->findOrFail($id);
            }
        );
    }

    public function getStats(): ?array {
        return Cache::remember('employee_stat:all', now()->addHour(), fn() => [
            'total_employees'    => Employee::count(),
            'total_active'       => Employee::active()->count(),
            'total_inactive'     => Employee::where('is_active', false)->count(),
            'total_regular'      => Employee::where('employment_type', EmploymentType::Regular)->count(),
        ]);
    }

    public function save(array $data): ?Employee {
        $employeeUser = new User();
        $employeeUser->name = $data['first_name'] . ' ' . $data['last_name'];
        $employeeUser->email = $data['email'];
        $employeeUser->password = Hash::make($data['password']);
        $employeeUser->save();

        $employeeUser->assignRole('employee');

        $employee = new $this->employee;
        $employee->first_name = $data['first_name'];
        $employee->middle_name = $data['middle_name'] ?? null;
        $employee->last_name = $data['last_name'];
        $employee->gender = $data['gender'];
        $employee->email = $data['email'];
        $employee->date_of_birth = $data['date_of_birth'];
        $employee->phone_number = $data['phone_number'];
        $employee->position_id = $data['position_id'];
        $employee->department_id = $data['department_id'];
        $employee->is_active = $data['is_active'];
        $employee->employment_type = $data['employment_type'];
        $employee->user_id = $employeeUser->id;
        $employee->saveQuietly();

        $employeeSalary = new Salary();
        $employeeSalary->employee_id = $employee->id;
        $employeeSalary->amount = $data['salary']['amount'];
        $employeeSalary->salary_type = $data['salary']['salary_type'];
        $employeeSalary->salary_grade = $data['salary']['salary_grade'];
        $employeeSalary->step = $data['salary']['step'];
        $employeeSalary->save();

        $employeeObserver = new EmployeeObserver();
        $employeeObserver->created($employee);

        $employeeAddress = new Address();
        $employeeAddress->employee_id = $employee->id;
        $employeeAddress->country = $data['address']['country'];
        $employeeAddress->zip_code = $data['address']['zip_code'];
        $employeeAddress->city = $data['address']['city'];
        $employeeAddress->address = $data['address']['address'];
        $employeeAddress->province = $data['address']['province'];
        $employeeAddress->save();

        $employeeWorkSchedule = new EmployeeWorkSchedule();
        $employeeWorkSchedule->employee_id = $employee->id;
        $employeeWorkSchedule->work_schedule_id = $data['work_schedule_id'];
        $employeeWorkSchedule->save();

        event(new Registered($employeeUser));

        return $employee->fresh();
    }

    public function update(int $id, array $data): ?Employee {
        $employee = $this->getById($id);
        $employee->update([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'position_id' => $data['position_id'],
            'department_id' => $data['department_id'],
            'is_active' => $data['is_active'],
            'employment_type' => $data['employment_type'],
        ]);

        $employeeAddress = $employee->address();
        $employeeAddress->update([
            'country' => $data['address']['country'],
            'zip_code' => $data['address']['zip_code'],
            'city' => $data['address']['city'],
            'address' => $data['address']['address'],
            'province' => $data['address']['province'],
        ]);

        $employeeSalary = $employee->salary();
        $employeeSalary->update([
            'amount' => $data['salary']['amount'],
            'salary_type' => $data['salary']['salary_type'],
            'salary_grade' => $data['salary']['salary_grade'],
            'step' => $data['salary']['step'],
        ]);

        $employeeWorkSchedule = $employee->employeeWorkSchedule();
        $employeeWorkSchedule->update([
           'work_schedule_id' => $data['work_schedule_id'],
        ]);

        return $employee->fresh();
    }

    public function deleteById(int $id): void {
        $employee = $this->employee->find($id);
        $employee->is_active = false;
        $employee->delete();
        Cache::forget("employee:{$id}");
    }

    public function exists(int $id): bool {
        return $this->employee->getById($id)->exists();
    }
}
