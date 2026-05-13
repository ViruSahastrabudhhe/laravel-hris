<?php

namespace App\Http\Requests\Performance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIPCREntryRequest extends FormRequest
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
            'ipcr_form_id' => ['required', 'integer', 'exists:ipcr_forms,id'],

            'kra' => ['required', 'array', 'min:1'],
            'objectives' => ['required', 'array', 'min:1'],
            'success_indicators' => ['required', 'array', 'min:1'],

            'kra.*' => ['required', 'string', 'max:255'],
            'objectives.*' => ['required', 'string', 'max:1000'],
            'success_indicators.*' => ['required', 'string', 'max:1000'],
        ];
    }
}
