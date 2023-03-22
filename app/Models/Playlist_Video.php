<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        return $this->HasOne(Video::class, 'id', 'video_id');
    }

    public function playlist(): HasOne
    {
        return $this->hasOne(Playlist::class, 'id', 'playlist_id');
    }
}
