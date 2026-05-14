<?php

namespace App\Http\Controllers\Admin\Performance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\ApproveIPCRFormRequest;
use App\Http\Requests\Performance\StoreIPCREntryRequest;
use App\Http\Requests\Performance\StoreIPCRFormRequest;
use App\Http\Requests\Performance\StorePerformanceCycleRequest;
use App\Http\Requests\Performance\UpdateIPCREntryRequest;
use App\Models\Department;
use App\Models\PayPeriod;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IPCRForm;
use App\Models\IPCREntry;
use App\Models\IPCRDevelopmentNeed;
use App\Models\PerformanceCycle;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class PerformanceManagementController extends Controller
{
    public function index()
    {
        return view('admin.performance.index', [
            'ipcrForms' => IPCRForm::with('employee', 'entries', 'developmentNeeds')->latest()->get(),
            'cycles' => PerformanceCycle::all(),
            'employees' => Employee::all(),
        ]);
    }

    public function create()
    {
        return redirect()->route('performance_management.index');
    }

    public function storePerformanceCycle(StorePerformanceCycleRequest $request) {
        $data = $request->validated();

        PerformanceCycle::create($data);

        return redirect()->route('performance_management.index')->with('success', 'Performance cycle created.');
    }

    public function storeIpcr(StoreIPCRFormRequest $request)
    {
        $data = $request->validated();

        IPCRForm::create($data);

        return back()->with('success', 'IPCREntry Form created.');
    }

    public function bulkStoreIpcr(Request $request) {
        $validated = $request->validate([
            'performance_cycle_id' => ['required', 'exists:performance_cycles,id'],
        ]);

        $cycleId = $validated['performance_cycle_id'];

        DB::transaction(function () use ($cycleId) {
            $employees = Employee::all();

            foreach ($employees as $employee) {
                $exists = IpcrForm::where('employee_id', $employee->id)
                    ->where('performance_cycle_id', $cycleId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                IpcrForm::create([
                    'employee_id' => $employee->id,
                    'performance_cycle_id' => $cycleId,
                ]);
            }
        });

        return redirect()->back()->with('success', 'IPCR forms successfully created for all employees.');
    }

    public function updateIpcr(UpdateIPCREntryRequest $request, IPCREntry $entry) {
        $data = $request->validated();

        foreach ($data['entries'] as $id => $data) {

            $average = ($data['quality_rating'] + $data['efficiency_rating'] + $data['timeliness_rating']) / 3;
            $entry = IPCREntry::find($id);

            if ($entry) {
                $entry->actual_accomplishments = $data['actual_accomplishments'];
                $entry->quality_rating = $data['quality_rating'];
                $entry->efficiency_rating = $data['efficiency_rating'];
                $entry->timeliness_rating = $data['timeliness_rating'];
                $entry->average_rating = $average;

                $entry->save();
            }
        }

        return back()->with('success', 'Ratings updated.');
    }

    public function computeFinalRating(IPCRForm $form) {
        if ($form->entries->isEmpty()) {
            return back()->with('error', 'No entries found!');
        }

        $final = $form->entries()->avg('average_rating');

        $form->update(['final_rating' => round($final, 2)]);

        return back()->with('success', 'Final rating computed.');
    }

    public function activateCycle(PerformanceCycle $cycle) {
        $cycles = PerformanceCycle::all();
        $cycles->update(['is_active' => false]);

        $cycle->update(['is_active' => true]);

        return redirect()->route('performance_management.index')->with('success', 'Cycle toggled on.');
    }

    public function deactivateCycle(PerformanceCycle $cycle) {
        $cycle->update(['is_active' => false]);

        return redirect()->route('performance_management.index')->with('success', 'Cycle toggled off.');
    }

    public function submit(IPCRForm $form) {
        $form->update(['status' => 'Submitted']);
        return back()->with('success', 'IPCREntry submitted.');
    }

    public function approve(ApproveIPCRFormRequest $request, IPCRForm $form) {
        $data = $request->validated();

        IPCRDevelopmentNeed::create($data);
        $form->update(['status' => 'Approved']);

        return back()->with('success', 'IPCR Form approved!');
    }

    public function storeDevelopmentNeed(Request $request) {
        $request->validate([
            'ipcr_form_id' => 'required',
            'development_needs' => 'required',
        ]);

        IPCRDevelopmentNeed::create([
            'ipcr_form_id' => $request->ipcr_form_id,
            'development_needs' => $request->development_needs,
            'recommended_training' => $request->recommended_training,
        ]);

        return back()->with('success', 'Development need saved.');
    }

    public function destroy(IPCRForm $form) {
        $form->delete();

        return back()->with('success', 'IPCR Form deleted.');
    }

    public function destroyCycle(PerformanceCycle $cycle) {
        $cycle->delete();

        return redirect()->route('performance_management.index')->with('success', 'Cycle deleted.');
    }
}
