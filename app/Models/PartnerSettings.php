<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_method_id',
    ];

    protected $attributes = [
        'contact_method_id' => 1,
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    } 
}
