<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'is_social_auth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
    
    /**
     * Retrieve the bookings associated with the user.
     * Why not use hasMany instead? To answer:
     * It to handle cases where user booking is not a registered user.
    */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'user_bookings');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'user_reservations');
    }

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'user_payments');
    }
}
