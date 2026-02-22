<?php

namespace App\Http\Controllers\Deduction;

use App\Models\Deduction;
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
        $deductions = Deduction::findAllWithUserID()->get();

        return view('deduction.index', ['deductions' => $deductions]);
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
    public function store(StoreDeductionRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeductionRequest $request, Deduction $deduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        //
    }
}
