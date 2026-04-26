<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'address.country' => 'required',
            'address.zip_code' => 'required',
            'address.city' => 'required',
            'address.address' => 'required',
            'address.province' => 'required',
            'address.user_id' => 'required',
            'phone_number' => 'required',
            'employment_type' => 'required',
            'is_active' => 'required',
            'position_id' => 'required',
            'department_id' => 'required',
            'work_schedule_id' => 'required',
            'password' => 'required|min:8|confirmed|string',
            'user_id' => 'required',
            'salary.salary_type' => 'required',
            'salary.amount' => 'required|numeric|min:0',
            'salary.salary_grade' => 'required|integer',
            'salary.step' => 'required|integer',
        ];
    }
}
