<?php

namespace App\Traits;

use App\Models\ReferenceNumberSettings;
use Illuminate\Database\Eloquent\Model;

/**
 * This trait requires a few prerequisites to function correctly.
 *
 * 1. This trait requires that the model it is used in has a 'reference' attribute.
 *    This attribute will be used to store the generated reference number.
 *    However, you may override the 'getReferenceName' method on the Eloquent model.
 *
 * 2. You must set the ReferenceNumberSetting for your specific model.
 *    This setting defines the prefix, length, and padding for the reference number.
 *    You can do this by creating a new instance of ReferenceNumberSetting and
 *    setting the relevant properties.
 *
 *    Once these prerequisites are met, this trait will automatically generate a
 *    unique reference number for each instance of the model.
 */

trait HasReferenceNumber
{
    public static function bootHasReferenceNumber(): void
    {
        static::created(function (Model $model) {
            $reference = $model->getReferenceName();
            $model->{$reference} = $model->generateUniqueReference();
            $model->save();
        });
    }

    public function generateUniqueReference(): array | string
    {
        $modelkey = static::getModelKey($this);

        try {
            /**
             * Usage of 'get_class()' to get the class name of the model
             * e.g. 'App\Models\Booking'
             */
            $settings = ReferenceNumberSettings::firstWhere('type', get_class($this));

            $referenceLength = $settings->length;
            $referencePrefix = $settings->prefix;
            $referenceSeparator = $settings->separator;
            $referencePadCharacter = $settings->pad_character;
            $referencePadDirection = $this->getPadDirection($settings->pad_direction);
    
            $key = str_pad($modelkey, $referenceLength, $referencePadCharacter, $referencePadDirection);   
            
            $reference = $this->merge($key, $referencePrefix, $referenceSeparator);
    
            return $reference;
        } catch (\Exception $exception) {
            return [
                'status'  => config('constant.toast.error'),
                'message' => 'Something went wrong while generating reference number. Please contact support.',
                'error'   => $exception->getMessage(),
                'code'    => $exception->getCode(),
            ];
        }
    }

    public function getReferenceName(): string
    {
        return 'reference';
    }

    private function getModelKey(Model $model): string
    {
        return (string) $model->getKey();
    }

    private function getPadDirection(string $direction): int
    {
        return $direction === ReferenceNumberSettings::LEFT_PAD 
                ? STR_PAD_LEFT 
                : STR_PAD_RIGHT;
    }

    private function merge($key, $prefix, $separator = null): string
    {
        return is_null($separator) ? $prefix . $key : $prefix . $separator . $key;
    }
}