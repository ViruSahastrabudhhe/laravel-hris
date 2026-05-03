<?php

namespace App\Http\Requests\Training;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeTrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id'     => 'required|integer|exists:employees,id',
            'training_id'     => 'required|integer|exists:trainings,id',
            'status'          => 'required|string',
            'completion_date' => 'nullable|date',
            'remarks'         => 'nullable|string',
            'user_id'         => 'required',
        ];
    }
}
