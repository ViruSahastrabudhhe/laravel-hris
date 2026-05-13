<?php

namespace App\Http\Requests\Performance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIPCREntryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entries.*.actual_accomplishments' => 'required',
            'entries.*.quality_rating' => 'required|numeric',
            'entries.*.efficiency_rating' => 'required|numeric',
            'entries.*.timeliness_rating' => 'required|numeric',
        ];
    }
}
