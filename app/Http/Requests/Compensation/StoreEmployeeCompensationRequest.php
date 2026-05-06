<?php

namespace App\Http\Requests\Compensation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeCompensationRequest extends FormRequest
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
            'compensation_id' => 'required',
            'amount' => 'required|numeric',
            'user_id' => 'required',
        ];
    }
}
