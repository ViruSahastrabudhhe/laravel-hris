<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'date' => 'required',
            'time_in' => 'nullable',
            'time_out' => 'nullable',
            'break_start' => 'nullable',
            'break_end' => 'nullable',
            'overtime_in' => 'nullable',
            'overtime_out' => 'nullable',
            'correction.remarks' => 'required',
            'correction.proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        dd($validator->errors()->all());
    }
}
