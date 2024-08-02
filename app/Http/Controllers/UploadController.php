<?php

namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UploadController extends Controller
{
    public function __construct(
        protected UploadService $uploadService
    ) {}

    // This is likely only for SSM uploads.
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => ['required', 'file', 'mimes:png,pdf', 'max:5120'],
            ]);
    
            $path = $this->uploadService->temporaryUpload($request->file('file'));

            return response()->json([
                'path' => $path,
            ]);
        } catch (ValidationException $exception) {
            Log::error('Validation failed: ' . json_encode($exception->errors()));
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            Log::error('File Upload Failed: ' . $exception->getMessage());
            return response()->json(['error' => 
                'File upload failed: ' . $exception->getMessage()
            ], 500);
        }
    }

    public function destroy(string $path) 
    {
        // Does user have authorization?

        $this->uploadService->delete($path);
    }
}