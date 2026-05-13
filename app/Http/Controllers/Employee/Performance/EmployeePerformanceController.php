<?php

namespace App\Http\Controllers\Employee\Performance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\StoreIPCREntryRequest;
use App\Models\Employee;
use App\Models\IPCREntry;
use App\Models\IPCRForm;
use Illuminate\Http\Request;

class EmployeePerformanceController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        $ipcrForms = IPCRForm::where('employee_id', $employee->id)->get();

        return view('employee.performance.index', compact(
            'ipcrForms',
        ));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function storeIpcrEntry(StoreIPCREntryRequest $request)
    {
        // 1. Retrieve the fully validated data array
        $validated = $request->validated();

        // 2. Loop through one of the arrays to map indices across all fields
        foreach ($validated['kra'] as $index => $kraValue) {
            IPCREntry::create([
                'ipcr_form_id'       => $validated['ipcr_form_id'],
                'kra'                => $kraValue,
                'objectives'         => $validated['objectives'][$index],
                'success_indicators' => $validated['success_indicators'][$index],
            ]);
        }

        return redirect()->back()->with('success', 'All KRA entries saved successfully!');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
