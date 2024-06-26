<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupTour extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'user_id',
        'max_passenger',
        'current_passenger',
        'description',
        'status',
        'is_private',
        'is_locked',
        'published_at',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
