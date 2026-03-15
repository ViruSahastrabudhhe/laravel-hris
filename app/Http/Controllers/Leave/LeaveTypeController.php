<?php

namespace App\Http\Controllers\Leave;

use App\Models\LeaveType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\StoreLeaveTypeRequest;
use App\Http\Requests\Leave\UpdateLeaveTypeRequest;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::findAllWithUserID()->get();

        return view('leave_type.index', ['leaveTypes' => $leaveTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveTypeRequest $request)
    {
        $data = $request->validated();

        LeaveType::create($data);

        return redirect()->route('leave_types.index')->with('success', __('leave_type.success_creating'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leave_type)
    {
        return redirect()->route('leave_types.index')->with('success', __('leave_type.show_not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leave_type)
    {
        return view('leave_type.edit', ['leaveType' => $leave_type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveTypeRequest $request, LeaveType $leave_type)
    {
        $data = $request->validated();

        $leave_type->update($data);

        return redirect()->route('leave_types.index')->with('success', __('leave_type.success_editing'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leave_type)
    {
        $leave_type->delete();

        return redirect()->route('leave_types.index')->with('success', __('leave_type.success_deleting'));
    }
}
