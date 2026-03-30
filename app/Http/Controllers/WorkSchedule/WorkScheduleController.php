<?php

namespace App\Http\Controllers\WorkSchedule;

use App\Models\WorkSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSchedule\StoreWorkScheduleRequest;
use App\Http\Requests\WorkSchedule\UpdateWorkScheduleRequest;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = WorkSchedule::findAllWithUserID()->get();

        return view('work_schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('work_schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkScheduleRequest $request)
    {
        $data = $request->validated();

        WorkSchedule::create($data);

        return redirect()->route('work_schedules.index')->with('success', __('schedule.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSchedule $workSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSchedule $workSchedule)
    {
        return view('work_schedule.edit', ['work_schedule' => $workSchedule]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkScheduleRequest $request, WorkSchedule $workSchedule)
    {
        $data = $request->validated();

        $workSchedule->update($data);

        return redirect()->route('work_schedules.index')->with('success', __('schedule.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSchedule $workSchedule)
    {
        //
    }
}
