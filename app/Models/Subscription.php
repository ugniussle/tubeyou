<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'channel_id'
    ];

    public function subscriber(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function channel(): BelongsTo {
        return $this->belongsTo(User::class, 'channel_id', 'id');
    }
}
