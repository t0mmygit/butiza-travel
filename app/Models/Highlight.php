<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Highlight extends Model
{
    use HasFactory;

    public function tour(): HasOne
    {
        return $this->hasOne(Tour::class);
    }
}
