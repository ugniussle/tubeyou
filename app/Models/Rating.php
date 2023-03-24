<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'video_id',
        'type'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function video(): BelongsTo {
        return $this->belongsTo(Video::class, 'id', 'video_id');
    }
}
