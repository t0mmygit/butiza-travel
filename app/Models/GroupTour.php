<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTour extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tours_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
