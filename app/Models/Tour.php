<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tour extends Model
{
    use HasFactory;

    public function itineraries(): HasOne
    {
        return $this->hasOne(Itinerary::class);
    }

    public function destinations(): BelongsToMany
    {
        // This is referring to the imtermediate table; not directly to Destination table
        return $this->belongsToMany(Destination::class, 'tour_destinations');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'tour_activities');
    }

    public function note(): HasOne
    {
        return $this->hasOne(Note::class);
    }
}
