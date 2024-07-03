<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomizeRequest extends FormRequest
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
            'start_date'       => 'date',
            'min_budget'       => 'numeric',
            'max_budget'       => 'numeric',
            'min_duration'     => 'numeric',
            'max_duration'     => 'numeric',
            'lodge_type'       => 'string',
            'guide_type'       => 'string',
            'travel_intensity' => 'string',
            'activities'       => 'array',
            'destinations'     => 'array',
        ];
    }
}
