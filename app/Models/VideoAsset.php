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
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $video_id
 * @property string $thumbnail_full
 * @property string $thumbnail_small
 * @property string $thumbnail_original
 * @property string $video_1080p
 * @property string $video_720p
 * @property string $video_480p
 * @property string $video_360p
 * @property string $video_240p
 * @property string $video_original
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereThumbnailFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereThumbnailOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereThumbnailSmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideo1080p($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideo240p($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideo360p($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideo480p($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideo720p($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoAsset whereVideoOriginal($value)
 * @mixin \Eloquent
 */
class VideoAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'thumbnail_full',
        'thumbnail_small',
        'video_1080p',
        'video_720p',
        'video_480p',
        'video_360p',
        'video_240p',
        'video_original'
    ];

    public function video(): BelongsTo {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
}
