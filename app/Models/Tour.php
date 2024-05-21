<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tour extends Model
{
    use HasFactory;

    public function itineraries()
    {
        return $this->hasOne(Itinerary::class);
    }

    public function destinations(): BelongsToMany
    {
        // This is referring to the imtermediate table; not directly to Destination table
        return $this->belongsToMany(Destination::class, 'tour_destinations');
    }
}
