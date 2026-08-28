@php
    $preview = $item->previewUrl();
    $type = 'image';
    $src = $item->url;
    if ($item->isYoutube() && $item->youtubeEmbedUrl()) {
        $type = 'youtube';
        $src = $item->youtubeEmbedUrl().'?autoplay=1';
    } elseif ($item->isCdnVideo()) {
        $type = 'video';
        $src = $item->url;
    }
    $label = $item->title ?: $item->category;
@endphp
<a href="#"
   class="gallery-tile"
   data-lightbox-src="{{ $src }}"
   data-lightbox-type="{{ $type }}">
    <div class="gallery-media">
        @if($preview)
            <img src="{{ $preview }}" alt="{{ $label ?: 'Gallery item' }}">
        @elseif($item->isCdnVideo())
            <video src="{{ $item->url }}" muted></video>
        @else
            <div class="gallery-fallback">Media</div>
        @endif
        @if(!$item->isImage())
            <div class="play"><span>▶</span></div>
        @endif
    </div>
    @if($label)
        <div class="gallery-caption">
            <strong>{{ $label }}</strong>
            @if($item->category && $item->title)
                <span>{{ $item->category }}</span>
            @endif
        </div>
    @endif
</a>
