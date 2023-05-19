<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Playlist
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $visibility
 * @property string $url_token
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist_Video> $playlistVideos
 * @property-read int|null $playlist_videos_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUrlToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereVisibility($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist_Video> $playlistVideos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist_Video> $playlistVideos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist_Video> $playlistVideos
 * @mixin \Eloquent
 */
class Playlist extends Model
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
        'visibility',
        'url_token'
    ];

    public function playlistVideos(): HasMany 
    {
        return $this->hasMany(Playlist_Video::class, 'playlist_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
