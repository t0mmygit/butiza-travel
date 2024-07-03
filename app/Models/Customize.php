<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customize extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'min_budget',
        'max_budget',
        'min_duration',
        'max_duration',
        'lodge_type',
        'guide_type',
        'travel_intensity',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_customizes');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'customize_activities');
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'customize_destinations');
    }

    public function ages(): BelongsToMany
    {
        return $this->belongsToMany(AgeRange::class, 'customize_ages');
    }
}
