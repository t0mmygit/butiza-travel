<?php

namespace App\Models;

use App\Traits\HasReferenceNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory, HasReferenceNumber;

    protected $fillable = [
        'package_id',
        'contact_method_id',
        'discount_id',
        'pickup_location_id',
        'preferred_date',
        'adult',
        'child',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'amount',
        'note',
        'status',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function contactMethod(): BelongsTo
    {
        return $this->belongsTo(ContactMethod::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_reservations');
    }
}