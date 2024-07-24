<?php

namespace App\Services;

use App\Models\Partner;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class PartnerRequestService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create($request): Partner
    {
        try {
            $validated = $request->validated();
            $validated['reference_code'] = $this->makeUniqueUUID();

        // Use make() instead of create() to instantiate the model without persisting to the database.
        // This allows us to defer storage until AFTER the user has created an account, preventing
        // incomplete records in the database.
        $partner = Partner::make($validated);

            return $partner;
        } catch (Exception $error) {
            Log::error('Failed to create partner request', [
                'error' => $error->getMessage(),
                'trace' => $error->getTraceAsString(),
            ]);

            throw new Exception(
                'Unable to create partner request: '. $error->getMessage() . 'Please contact support.', 
                0, 
                $error
            );
        }
    }

    protected function makeUniqueUUID(int $maximumAttempt = 5): string
    {   
        $attempts = 0;

        do {
            $uuid = Str::uuid()->toString();
            $attempts++;

            if ($attempts > $maximumAttempt) {
                throw new RuntimeException("Failed to generate unique UUID after {$maximumAttempt} attempts.");
            }
        } while (Partner::where('reference_code', $uuid)->exists());
        
        return (string) $uuid;
    }
}
