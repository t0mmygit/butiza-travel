<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string'],
            'website' => ['required', 'string','url:http,https'],
            'country' => ['required', 'string', 'alpha:ascii'],
            'city' => ['required', 'string','alpha:ascii'],
            'registration_number' => ['sometimes', 'nullable','string', 'alpha_num:ascii'],
            'ssm_path' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
