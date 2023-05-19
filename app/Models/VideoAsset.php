<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\VideoAsset
 *
 * @property-read \App\Models\Video $video
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset query()
 * @mixin \Eloquent
 */
class VideoAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'thumbnail_full',
        'thumbnail_small',
        'video_1080p',
        'video_720p',
        'video_480p',
        'video_360p',
        'video_240p'
    ];

    public function video(): BelongsTo {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
}
