<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $filename
 * @property string|null $description
 * @property string $thumbnail
 * @property int $views
 * @property int $likes
 * @property int $dislikes
 * @property string $upload_date
 * @property int $visibility
 * @property string $url_token
 * @property string $video_asset
 * @property string $thumbnail_asset
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDislikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereThumbnailAsset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUploadDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUrlToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereVideoAsset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereVisibility($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @mixin \Eloquent
 */
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

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'video_id', 'id');
    }

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'video_id', 'id');
    }
}
