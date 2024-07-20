<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'trip_status' => BookingStatus::class
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_bookings');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function contactMethod(): HasOne
    {
        return $this->hasOne(ContactMethod::class, 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
