<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Subject extends Model
{
    use HasFactory;

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function bulletPoints(): HasMany
    {
        return $this->hasMany(BulletPoint::class);  
    }
}
