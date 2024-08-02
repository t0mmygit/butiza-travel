<?php

namespace App\Http\Requests;

class PartnerBookingRequest extends BookingRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === config('constant.user_roles.partner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_id'        => 'required|numeric',
            'contact_method_id' => 'required|numeric',
            'departure_date'    => 'required|date',
            'finished_date'     => 'required|date',
            'adult'             => 'required_without:child|nullable|numeric|max:99',
            'child'             => 'required_without:adult|nullable|numeric|max:99',
            'note'              => 'string|nullable',
        ];
    }

    public function passedValidation(): void
    {
        $this->merge([parent::passedValidation(),
            'first_name'   => auth()->user()->first_name,
            'last_name'    => auth()->user()->last_name,
            'email'        => auth()->user()->email,
            'phone_number' => auth()->user()->phone_number,
        ]);
    }
}