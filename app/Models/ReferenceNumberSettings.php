<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceNumberSettings extends Model
{
    use HasFactory;

    const LEFT_PAD = 'left'; 
    const RIGHT_PAD = 'right';
}