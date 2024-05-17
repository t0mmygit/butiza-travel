<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    public function itineraries() 
    {
        return $this->belongsTo(Tour::class);
    }

    public function days() 
    {
        return $this->hasMany(Day::class);
    }
}
