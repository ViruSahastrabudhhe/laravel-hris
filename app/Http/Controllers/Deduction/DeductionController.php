<?php

namespace App\Http\Controllers\Deduction;

use App\Models\Deduction;
use App\Enums\DeductionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deduction\StoreDeductionRequest;
use App\Http\Requests\Deduction\UpdateDeductionRequest;

class DeductionController extends Controller
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
        $deductionType = DeductionType::cases();
        return view('deduction.create', ['deductionType' => $deductionType]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeductionRequest $request)
    {
        $data = $request->validated();

        Deduction::create($data);

        return redirect()->route('deductions.index')->with('success'. __('deduction.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deduction $deduction)
    {
        $deductionType = DeductionType::cases();
        return view('deduction.edit', ['deduction' => $deduction, 'deductionType' => $deductionType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeductionRequest $request, Deduction $deduction)
    {
        $data = $request->validated();

        $deduction->update($data);

        return redirect()->route('deductions.index')->with('success', __('deduction.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        $deduction->delete();

        return redirect()->route('deductions.index')->with('success', __(''));
    }
}
