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

    protected $fillable = [
        'package_id',
        'contact_method_id',
        'discount_id',
        'pickup_location_id',
        'departure_date',
        'finished_date',
        'adult',
        'child',
        'note',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'trip_status',
    ];

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

    public function contactMethod(): BelongsTo
    {
        return $this->belongsTo(ContactMethod::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(PickupLocation::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}