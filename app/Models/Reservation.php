<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
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
