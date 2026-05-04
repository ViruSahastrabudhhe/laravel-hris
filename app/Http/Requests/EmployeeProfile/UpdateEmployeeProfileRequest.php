<?php

namespace App\Http\Requests\EmployeeProfile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('employee');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
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
            'phone_number' => 'required',
        ];
    }
}
