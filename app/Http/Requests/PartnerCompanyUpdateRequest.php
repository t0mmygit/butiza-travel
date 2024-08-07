<?php

namespace App\Http\Requests;

use App\Services\UploadService;
use Illuminate\Foundation\Http\FormRequest;

class PartnerCompanyUpdateRequest extends FormRequest
{
    public function __construct(
        protected UploadService $uploadService
    ) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function prepareForValidation()
    {
        // [1] Store the file to the permanent storage
        if ($this->has('ssm_path') && str_starts_with($this->input('ssm_path'), 'temp/')) {
            $newPath = $this->uploadService->permanentUpload($this->input('ssm_path'));

            $this->merge([
                'ssm_path' => $newPath,
            ]);
        }

        if ($this->has('registration_number')) {
            $this->merge([
                'registration_number' => (string) $this->input('registration_number'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name'       => ['required', 'string'],
            'website'             => ['required', 'string','url:http,https'],
            'country'             => ['required', 'string', 'alpha:ascii'],
            'city'                => ['required', 'string','alpha:ascii'],
            'registration_number' => ['sometimes', 'nullable', 'string'],
            'ssm_path'            => ['sometimes', 'nullable', 'string'],
        ];
    }
}
