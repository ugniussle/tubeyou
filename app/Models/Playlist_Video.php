<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Playlist_Video
 *
 * @property int $playlist_id
 * @property int $video_id
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Playlist $playlist
 * @property-read \App\Models\Video|null $video
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video whereVideoId($value)
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist_Video whereId($value)
 * @mixin \Eloquent
 */
class Playlist_Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'playlist_id',
        'video_id',
        'position'
    ];

    public function video(): HasOne 
    {
        return $this->HasOne(Video::class, 'video_id', 'id');
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class, 'playlist_id', 'id');
    }
}
