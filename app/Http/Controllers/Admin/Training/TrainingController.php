<?php

namespace App\Http\Controllers\Admin\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Enums\TrainingStatus;
use App\Enums\TrainingType;
use App\Http\Requests\Training\StoreTrainingRequest;
use App\Http\Requests\Training\UpdateTrainingRequest;
use App\Models\EmployeeTraining;
use App\Enums\EmployeeTrainingStatus;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = Training::paginate(25);
        $trainingTypes = TrainingType::cases();
        $trainingStatuses = TrainingStatus::cases();

        return view('admin.training.index', compact('trainings', 'trainingTypes', 'trainingStatuses'));
    }

    public function participants(Training $training)
    {
        $participants = $training->employeeTrainings()
            ->with('employee')
            ->get()
            ->map(fn($et) => [
                'id'              => $et->id,
                'name'            => $et->employee->first_name . ' ' . $et->employee->last_name,
                'status'          => $et->status,
                'completion_date' => $et->completion_date,
                'remarks'         => $et->remarks,
            ]);

        return response()->json($participants);
    }

    public function declineParticipant(Request $request, EmployeeTraining $employeeTraining)
    {
        $employeeTraining->update([
            'status'  => EmployeeTrainingStatus::Declined->value,
            'remarks' => $request->input('remarks'),
        ]);

        return response()->json(['success' => true]);
    }

    public function approveParticipant(EmployeeTraining $employeeTraining)
    {
        $employeeTraining->update(['status' => EmployeeTrainingStatus::Enrolled->value]);
        $employeeTraining->training->increment('participants');

        return response()->json(['success' => true]);
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
    public function store(StoreTrainingRequest $request)
    {
        $data = $request->validated();

        Training::create($data);

        return redirect()->route('trainings.index')->with('success', __('training.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainingRequest $request, Training $training)
    {
        $data = $request->validated();

        $training->update($data);
        
        if ($training->status === TrainingStatus::Ongoing->value) {
            $employeeTraining = EmployeeTraining::where('training_id', $training->id)->get();
            foreach ($employeeTraining as $et) {
                $et->update(['status' => EmployeeTrainingStatus::Ongoing->value]);
            }
        }
        if ($training->status === TrainingStatus::Completed->value) {
            $employeeTraining = EmployeeTraining::where('training_id', $training->id)->get();
            foreach ($employeeTraining as $et) {
                $et->update(['status' => EmployeeTrainingStatus::Completed->value]);
            }
        }

        return redirect()->route('trainings.index')->with('success', __('training.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('trainings.index')->with('success', __('training.success_deleting'));
    }
}
