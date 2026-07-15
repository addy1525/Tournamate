<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'fixture_id',
        'field_name',
        'title',
        'provider',
        'video_id',
        'stream_url',
        'status',
        'viewers',
    ];

    // Status Constants
    const STATUS_LIVE      = 'live';
    const STATUS_OFFLINE   = 'offline';
    const STATUS_SCHEDULED = 'scheduled';

    // Provider Constants
    const PROVIDER_YOUTUBE = 'youtube';
    const PROVIDER_TWITCH  = 'twitch';
    const PROVIDER_CUSTOM  = 'custom';

    // Relationships
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    // Helper: generate embed URL based on provider
    public function getEmbedUrlAttribute(): string
    {
        return match ($this->provider) {
            'youtube' => "https://www.youtube.com/embed/{$this->video_id}?autoplay=1&rel=0",
            'twitch'  => "https://player.twitch.tv/?channel={$this->video_id}&parent=" . parse_url(config('app.url'), PHP_URL_HOST),
            'custom'  => $this->stream_url ?? '',
            default   => '',
        };
    }

    // Helper: generate thumbnail URL
    public function getThumbnailUrlAttribute(): string
    {
        return match ($this->provider) {
            'youtube' => "https://img.youtube.com/vi/{$this->video_id}/mqdefault.jpg",
            default   => '',
        };
    }

    // Helper: generate watch URL
    public function getWatchUrlAttribute(): string
    {
        return match ($this->provider) {
            'youtube' => "https://www.youtube.com/watch?v={$this->video_id}",
            'twitch'  => "https://www.twitch.tv/{$this->video_id}",
            default   => $this->stream_url ?? '#',
        };
    }

    // Scope: only live streams
    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }
}
