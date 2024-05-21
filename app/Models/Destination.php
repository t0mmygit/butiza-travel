<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    use HasFactory;

    public function tours(): BelongsToMany
    {
        // This is referring to an imtermediate table (tour_destinations), not directly
        return $this->belongsToMany(Tour::class, 'tour_destinations');
    }
}
