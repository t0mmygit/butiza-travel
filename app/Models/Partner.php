<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}