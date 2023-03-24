<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'filename',
        'description',
        'thumbnail',
        'visibility',
        'url_token',
        'video_asset',
        'thumbnail_asset',
    ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
