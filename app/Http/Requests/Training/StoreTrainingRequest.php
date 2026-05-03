<?php

namespace App\Http\Requests\Training;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingRequest extends FormRequest
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
            'program_title' => 'required|string|max:255',
            'type'          => 'required',
            'capacity'      => 'required|integer|min:1',
            'participants'  => 'nullable|integer|min:1',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'venue'         => 'required|string|max:255',
            'status'        => 'required|string',
            'user_id'       => 'required',
        ];
    }
}
