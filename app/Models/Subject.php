<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function bulletPoints()
    {
        return $this->hasMany(BulletPoint::class);  
    }
}
