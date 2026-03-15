<?php

namespace App\Http\Controllers\Leave;

use App\Models\Holiday;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreHolidayRequest;
use App\Http\Requests\Leave\UpdateHolidayRequest;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::findAllWithUserID()->get();

        return view('holiday.index', ['holidays' => $holidays]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayRequest $request)
    {
        $data = $request->validated();

        Holiday::create($data);

        return redirect()->route('holidays.index')->with('success', __('holidays.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        return redirect()->route('holidays.index')->with('success', __('holidays.show_not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        return view('holiday.edit', ['holiday' => $holiday]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $data = $request->validated();

        $holiday->update($data);

        return redirect()->route('holidays.index')->with('success', __('holidays.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', __('holidays.success_deleting'));
    }
}
