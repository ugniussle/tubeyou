<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_picture'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'user_id', 'id');
    }

    public function videos(): HasMany {
        return $this->hasMany(Video::class, 'user_id', 'id');
    }

    public function subscriptions(): HasMany {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function subscribers(): HasMany {
        return $this->hasMany(Subscription::class, 'recipient_id', 'id');
    }

    public function playlists(): HasMany {
        return $this->hasMany(Playlist::class, 'user_id', 'id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
