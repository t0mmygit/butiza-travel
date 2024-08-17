<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'package_id'         => 'package',
            'contact_method_id'  => 'contact method',
            'pickup_location_id' => 'pickup location',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_id'         => ['required', 'exists:packages,id'],
            'contact_method_id'  => ['required', 'exists:contact_methods,id'],
            'discount_id'        => ['sometimes', 'nullable', 'exists:discounts,id'],
            'pickup_location_id' => ['required', 'exists:pickup_locations,id'],
            'departure_date'     => ['required', 'date'],
            'finished_date'      => ['required', 'date', 'after:departure_date'],
            'adult'              => ['required_with:child', 'numeric', 'max:99'],
            'child'              => ['sometimes', 'nullable', 'numeric', 'max:99'],
            'note'               => ['string', 'nullable'],
            'first_name'         => ['required', 'string'],
            'last_name'          => ['required', 'string'],
            'email'              => ['required', 'string', 'email:rfc,dns', 'lowercase'],
            'phone_number'       => ['required', 'string'],
            'amount'             => ['required', 'decimal:0,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'preferred_date.required' => 'The date field is required.',
            'adult.required_with' => 'The traveller field is required.',
        ];
    }
}