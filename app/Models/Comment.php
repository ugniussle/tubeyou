<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
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
        'body',
        'parent'
    ];

    protected $with = ['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent', 'id');
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'id', 'parent');
    }
}
