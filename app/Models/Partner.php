<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'business_name',
        'city',
        'website',
        'message',
        'registration_number',
        'ssm_path',
    ];

    protected static function booted(): void
    {
        static::created(function ($partner) {
            $partner->settings()->create();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(PartnerSettings::class);
    }
}