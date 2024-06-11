<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_bookings');
    }

    public function tour(): HasOne
    {
        return $this->hasOne(Tour::class, 'id');
    }

    public function contactMethod(): HasOne
    {
        return $this->hasOne(ContactMethod::class, 'id');
    }
}
