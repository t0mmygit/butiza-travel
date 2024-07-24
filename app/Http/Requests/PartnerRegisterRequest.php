<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRegisterRequest extends RegisterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'phone_number' => ['required', 'numeric'],
        ]);
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'role' => 'partner',
        ]);
    }
}