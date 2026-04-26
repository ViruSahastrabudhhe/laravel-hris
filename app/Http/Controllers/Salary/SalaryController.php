<?php

namespace App\Http\Controllers\Salary;

use App\Models\Salary;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\StoreSalaryRequest;
use App\Http\Requests\Salary\UpdateSalaryRequest;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('employee_deductions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::findAllWithUserID()->get();
        return view('salary.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Salary::create($data);

        return redirect()->route('salaries.index')->with('success', 'Salary created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $employees = Employee::findAllWithUserID()->get();
        return view('salary.edit', compact('salary', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        $data = $request->validated();
        $salary->update($data);

        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary deleted successfully.');
    }
}
