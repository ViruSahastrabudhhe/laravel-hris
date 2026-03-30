<?php

namespace App\Http\Requests\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkScheduleRequest extends FormRequest
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
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'pm_start_time' => 'required',
            'break_minutes' => 'required',
            'work_days' => 'nullable|array',
            'work_days.*' => 'string',
            'grace_period_minutes' => 'required',
            'user_id' => 'required',
        ];
    }
}
