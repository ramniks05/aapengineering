<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'type',
        'url',
        'thumbnail_url',
        'category',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
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

    public function previewUrl(): ?string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        if ($this->isImage()) {
            return $this->url;
        }

        if ($this->isYoutube() && $this->youtubeId()) {
            return 'https://img.youtube.com/vi/'.$this->youtubeId().'/hqdefault.jpg';
        }

        return null;
    }
}
