<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'note_id');
    }
}
