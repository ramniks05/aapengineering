@extends('layouts.app')

@section('title', $project->title.' | AAP Engineerings')
@section('meta', $project->short_description)

@section('content')
<div class="container page-hero">
    <div class="meta" style="margin-bottom:1rem;">
        <span class="badge {{ $project->status }}">{{ $project->statusLabel() }}</span>
        @if($project->city)
            <span class="badge">{{ $project->city->name }}{{ $project->city->state ? ', '.$project->city->state : '' }}</span>
        @endif
        @if($project->project_type)
            <span class="badge">{{ $project->project_type }}</span>
        @endif
    </div>
    <h1>{{ $project->title }}</h1>
    <p class="lede">{{ $project->short_description }}</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container split">
        <div>
            @if($project->cover_image_url)
                <div class="gallery-item" style="margin-bottom:1rem;">
                    <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}">
                </div>
            @endif

            <div class="panel" style="margin-bottom:1.25rem;">
                <h2>Project overview</h2>
                <div style="white-space:pre-line; color:var(--muted);">{{ $project->description }}</div>
            </div>

            <h2 style="font-family:var(--font-display); margin:0 0 1rem;">Gallery</h2>
            <div class="gallery">
                @forelse($project->media as $media)
                    <div class="gallery-item">
                        @if($media->isImage())
                            <img src="{{ $media->url }}" alt="{{ $media->caption ?: $project->title }}">
                        @elseif($media->isYoutube() && $media->youtubeEmbedUrl())
                            <iframe src="{{ $media->youtubeEmbedUrl() }}" title="{{ $media->caption ?: $project->title }}" allowfullscreen loading="lazy"></iframe>
                        @elseif($media->isCdnVideo())
                            <video controls preload="metadata" poster="{{ $media->thumbnail_url }}">
                                <source src="{{ $media->url }}">
                                Your browser does not support video playback.
                            </video>
                        @endif
                        @if($media->caption)
                            <div class="caption">{{ $media->caption }}</div>
                        @endif
                    </div>
                @empty
                    <div class="panel">Media will appear here when added from admin.</div>
                @endforelse
            </div>
        </div>

        <aside class="panel">
            <h2>Details</h2>
            <p><strong>Status:</strong> {{ $project->statusLabel() }}</p>
            @if($project->client_name)
                <p><strong>Client:</strong> {{ $project->client_name }}</p>
            @endif
            @if($project->city)
                <p><strong>Location:</strong> {{ $project->city->name }}{{ $project->city->state ? ', '.$project->city->state : '' }}</p>
            @endif
            @if($project->start_date)
                <p><strong>Start:</strong> {{ $project->start_date->format('d M Y') }}</p>
            @endif
            @if($project->end_date)
                <p><strong>End:</strong> {{ $project->end_date->format('d M Y') }}</p>
            @endif
            <a href="{{ route('enquiry', ['interest' => $project->title]) }}" class="btn btn-primary" style="width:100%;margin-top:1rem;">Enquire about similar work</a>
        </aside>
    </div>

    @if($related->isNotEmpty())
        <div class="container" style="margin-top:3rem;">
            <div class="section-head">
                <h2>Related projects</h2>
            </div>
            <div class="grid-3">
                @foreach($related as $item)
                    @include('partials.project-card', ['project' => $item])
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
