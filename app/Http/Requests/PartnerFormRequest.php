<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerFormRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns'],
            'phone_number' => ['required', 'numeric'],
            'business_name' => ['required', 'string'],
            'website' => ['required', 'url'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'message' => ['nullable', 'string', 'max:255'],
        ];
    }
}