<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tour()
    {
        return $this->hasOne(Tour::class, 'id', 'tours_id');
    }

    public function contactMethod()
    {
        return $this->hasOne(ContactMethod::class, 'id', 'contact_methods_id');
    }
}
