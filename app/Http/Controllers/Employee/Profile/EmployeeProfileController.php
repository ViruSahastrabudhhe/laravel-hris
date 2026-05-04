<?php

namespace App\Http\Controllers\Employee\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\EmployeeProfile\StoreEmployeeProfileRequest;
use App\Http\Requests\EmployeeProfile\UpdateEmployeeProfileRequest;
use Illuminate\Support\Facades\Log;

class EmployeeProfileController extends Controller
{
    public function index() {
        $employeeId = Employee::where('user_id', auth()->id())->value('id');
        $employee = Employee::find($employeeId);

        return view('employee.profile.index', compact('employee'));
    }

    public function create() {
        //
    }

    public function store(StoreEmployeeProfileRequest $request) {
        //
    }

    public function show() {
        //
    }

    public function edit() {
        //
    }

    public function update(UpdateEmployeeProfileRequest $request, Employee $profile) {
        $data = $request->validated();

        $profile->update($data);

        Log::info('Employee profile updated: ' . $profile);

        return back()->with('success', __('profile.success_creating'));
    }

    public function destroy() {
        //
    }
}
