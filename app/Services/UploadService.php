<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function temporaryUpload(object $file): string
    {
        $subdirectory = 'temp';

        if (! Storage::disk('local')->exists($subdirectory)) {
            Storage::disk('local')->makeDirectory($subdirectory);
        }

        Storage::disk('local')->put($subdirectory, $file);
        
        $path = 'temp/' . $file->hashName();

        return $path;
    }

    public function permanentUpload(string $temporaryPath): string
    {
        $fileName = basename($temporaryPath);

        $file = Storage::disk('local')->get($temporaryPath);

        $newPath =  env('SHARED_STORAGE_PATH') . '\\ssm\\' . $fileName;

        Storage::disk('shared')->put($newPath, $file);

        Storage::disk('local')->delete($temporaryPath);

        return $newPath;
    }
    
    public function delete(string $path)
    {
        Storage::disk('local')->delete($path);
    }
}