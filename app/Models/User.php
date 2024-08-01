<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'role',
        'password',
        'is_social_auth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
     * Did not go with hasMany relationship to handle cases where 
     * user booking is not a registered user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
}
