<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'salary' => 'required|numeric',
            'employment_type' => 'required',
            'is_active' => 'required',
            'position_id' => 'required',
            'department_id' => 'required',
            'user_id' => 'required',
        ];
    }
}
