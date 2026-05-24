<?php

namespace App\Services\Employee;

use App\Models\Address;
use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\Salary;
use App\Models\User;
use App\Repositories\Employee\EmployeeRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class EmployeeService
{
    protected EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository) {
        $this->employeeRepository = $employeeRepository;
    }

    public function getAll(): ?Collection {
        return $this->employeeRepository->getAll();
    }

    public function getAllOnlyTrashed(): ?Collection {
        return $this->employeeRepository->getAllOnlyTrashed();
    }

    public function getEmployee(int $id): ?Employee {
        return $this->employeeRepository->getById($id);
    }

    public function getEmployeeStats(): ?array {
        return $this->employeeRepository->getStats();
    }

    /**
     * @throws \Throwable
     */
    public function saveEmployeeData(array $data): ?Employee {
        DB::beginTransaction();

        try {
            $employee = $this->employeeRepository->save($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to store employee data');
        }

        DB::commit();

        return $employee;
    }

    /**
     * @throws \Throwable
     */
    public function bulkSaveEmployeeData(UploadedFile $csv): ?array {
        $file = fopen($csv->getRealPath(), 'r');
        $header = fgetcsv($file);

        $errors = [];
        $row = 2;

        while (($data = fgetcsv($file)) !== false) {
            $record = array_combine($header, $data);

            $record['address'] = [
                'country'  => $record['country'] ?? null,
                'city'     => $record['city'] ?? null,
                'zip_code' => $record['zip_code'] ?? null,
                'address'  => $record['address'] ?? null,
                'province' => $record['province'] ?? null,
            ];

            $record['salary'] = [
                'salary_type'  => $record['salary_type'] ?? null,
                'amount'       => $record['amount'] ?? null,
                'salary_grade' => $record['salary_grade'] ?? null,
                'step'         => $record['step'] ?? null,
            ];

            if (User::where('email', $record['email'])->exists() || Employee::where('email', $record['email'])->exists()) {
                Log::info("Row {$row}: Email {$record['email']} already exists");
                $errors[] = "Row {$row}: Email {$record['email']} already exists.";
                $row++;
                continue;
            }

            DB::beginTransaction();

            try {
                $this->employeeRepository->save($record);
                $row++;
            } catch (Exception $e) {
                DB::rollBack();
                Log::info($e->getMessage());

                $row++;
                throw new InvalidArgumentException('Unable to store employee data');
//                $errors[] = "Row {$row}: Failed to import (check foreign keys). Error: " . $e->getMessage();
            }

            DB::commit();
        }

        fclose($file);

        return $errors;
    }

    /**
     * @throws \Throwable
     */
    public function updateEmployeeData(int $id, array $data): ?Employee {
        DB::beginTransaction();

        try {
            $employee = $this->employeeRepository->update($id, $data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update employee data');
        }

        DB::commit();

        return $employee;
    }

    public function restoreEmployee(int $id): ?Employee {
        $employee = $this->getEmployee($id);
        $employee->restore();
        $employee->is_active = true;
        $employee->save();

        return $employee->fresh();
    }

    public function activateEmployee(int $id): ?Employee {
        $employee = $this->getEmployee($id);
        $employee->is_active = true;
        $employee->save();

        return $employee->fresh();
    }

    public function deactivateEmployee(int $id): ?Employee {
        $employee = $this->getEmployee($id);
        $employee->is_active = false;
        $employee->save();

        return $employee->fresh();
    }

    /**
     * @throws \Throwable
     */
    public function deleteEmployee(int $id): void {
        DB::beginTransaction();

        try {
            $this->employeeRepository->deleteById($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete employee data');
        }

        DB::commit();
    }
}
