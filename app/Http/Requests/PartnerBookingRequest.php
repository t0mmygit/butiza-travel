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
            'package_id'        => ['required', 'exists:packages,id'],
            'contact_method_id' => ['required', 'exists:contact_methods,id'],
            'discount_id'       => ['required', 'exists:discounts,id'], 
            'departure_date'    => ['required', 'date'],
            'finished_date'     => ['required', 'date', 'after:departure_date'],
            'adult'             => ['required', 'numeric', 'max:99'],
            'child'             => ['required_with:adult', 'nullable', 'numeric', 'max:99'],
            'note'              => ['string', 'nullable'],
            'amount'            => ['required', 'decimal:0,2'],
        ];
    }

    public function passedValidation(): void
    {
        // NOTE: date is formatted in the parent class
        parent::passedValidation();

        $this->merge([
            'first_name'   => auth()->user()->first_name,
            'last_name'    => auth()->user()->last_name,
            'email'        => auth()->user()->email,
            'phone_number' => auth()->user()->phone_number,
        ]);
    }
}