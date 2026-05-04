<?php

namespace App\Http\Controllers\Employee\Training;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTraining;
use App\Models\Training;
use App\Models\Employee;
use App\Enums\TrainingStatus;
use App\Enums\TrainingType;
use App\Enums\EmployeeTrainingStatus;
use App\Http\Requests\Training\StoreEmployeeTrainingRequest;
use App\Http\Requests\Training\UpdateEmployeeTrainingRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myTrainings = EmployeeTraining::where('user_id', auth()->id())->get();
        $trainingTypes = TrainingType::cases();
        $trainingStatuses = TrainingStatus::cases();
        $availableTrainings = Training::isAvailable()->get();
        $enrolledTrainingIds = $myTrainings->pluck('training_id')->toArray();

        return view('employee.training.index', compact('myTrainings', 'availableTrainings', 'trainingTypes', 'trainingStatuses', 'enrolledTrainingIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeTrainingRequest $request)
    {
        $employee = Employee::where('email', auth()->user()->email)->firstOrFail();
        $training = Training::findOrFail($request->training_id);

        if (EmployeeTraining::where('employee_id', $employee->id)->where('training_id', $training->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this training.');
        }

        if ($training->participants >= $training->capacity) {
            return back()->with('error', 'This training is fully booked.');
        }

        EmployeeTraining::create([
            'employee_id' => $employee->id,
            'training_id' => $training->id,
            'user_id'     => auth()->id(),
            'status'      => EmployeeTrainingStatus::Pending->value,
        ]);

        return back()->with('success', 'Enrollment submitted successfully.');
    }

    public function downloadCertificate(EmployeeTraining $employeeTraining)
    {
        $this->authorize('view', $employeeTraining);

        $pdf = Pdf::loadView('employee.training.certificate', [
            'training'  => $employeeTraining->training,
            'employee'  => $employeeTraining->employee,
            'certNo'    => 'CERT-' . str_pad($employeeTraining->id, 4, '0', STR_PAD_LEFT),
            'completionDate' => $employeeTraining->completion_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('certificate-' . $employeeTraining->id . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeTraining $employeeTraining)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeTraining $employeeTraining)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeTrainingRequest $request, EmployeeTraining $employeeTraining)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeTraining $employeeTraining)
    {
        //
    }
}
