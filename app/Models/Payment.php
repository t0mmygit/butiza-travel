<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'status',
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_payments');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}