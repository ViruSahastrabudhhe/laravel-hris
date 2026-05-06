<?php

namespace App\Http\Controllers\Admin\Compensation;

use App\Models\Compensation;
use App\Enums\CompensationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Compensation\StoreCompensationRequest;
use App\Http\Requests\Compensation\UpdateCompensationRequest;

class CompensationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('employee_compensations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deductionType = CompensationType::cases();
        return view('deduction.create', ['deductionType' => $deductionType]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompensationRequest $request)
    {
        $data = $request->validated();

        Compensation::create($data);

        return redirect()->route('compensations.index')->with('success'. __('deduction.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Compensation $compensation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compensation $compensation)
    {
        $deductionType = CompensationType::cases();
        return view('deduction.edit', ['deduction' => $compensation, 'deductionType' => $deductionType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompensationRequest $request, Compensation $compensation)
    {
        $data = $request->validated();

        $compensation->update($data);

        return redirect()->route('compensations.index')->with('success', __('deduction.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compensation $compensation)
    {
        $compensation->delete();

        return redirect()->route('compensations.index')->with('success', __(''));
    }
}
