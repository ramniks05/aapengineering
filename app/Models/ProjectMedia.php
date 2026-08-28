<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProjectMedia extends Model
{
    protected $table = 'project_media';

    protected $fillable = [
        'project_id',
        'type',
        'url',
        'thumbnail_url',
        'caption',
        'sort_order',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isYoutube(): bool
    {
        return $this->type === 'video_youtube';
    }

    public function isCdnVideo(): bool
    {
        return $this->type === 'video_cdn';
    }

    public function youtubeId(): ?string
    {
        if (! $this->isYoutube()) {
            return null;
        }

        $url = $this->url;

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $url, $m)) {
            return $m[1];
        }

        if (preg_match('~^[A-Za-z0-9_-]{11}$~', $url)) {
            return $url;
        }

        return null;
    }

    public function youtubeEmbedUrl(): ?string
    {
        $id = $this->youtubeId();

        return $id ? 'https://www.youtube.com/embed/'.$id : null;
    }

    public function youtubeThumbnail(): ?string
    {
        if (filled($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        $id = $this->youtubeId();

        return $id ? 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg' : null;
    }

    public function displayUrl(): string
    {
        if ($this->isYoutube()) {
            return $this->youtubeEmbedUrl() ?? $this->url;
        }

        return $this->url;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'image' => 'Image (CDN)',
            'video_cdn' => 'Video (CDN)',
            'video_youtube' => 'Video (YouTube)',
            default => Str::headline($this->type),
        };
    }
}
