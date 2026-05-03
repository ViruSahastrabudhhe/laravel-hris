<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Enums\TrainingStatus;
use App\Enums\TrainingType;
use App\Http\Requests\Training\StoreTrainingRequest;
use App\Http\Requests\Training\UpdateTrainingRequest;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = Training::findAllWithUserID()->get();
        $trainingTypes = TrainingType::cases();
        $trainingStatuses = TrainingStatus::cases();

        return view('admin.training.index', compact('trainings', 'trainingTypes', 'trainingStatuses'));
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
