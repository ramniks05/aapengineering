@extends('layouts.app')

@section('title', $update->title.' | AAP Engineerings')
@section('meta', $update->excerpt)

@section('content')
<div class="container page-hero">
    <div class="meta" style="margin-bottom:1rem;">
        <span class="badge">{{ optional($update->published_at)->format('d M Y') ?: $update->created_at->format('d M Y') }}</span>
    </div>
    <h1>{{ $update->title }}</h1>
    @if($update->excerpt)
        <p class="lede">{{ $update->excerpt }}</p>
    @endif
</div>

<section class="section" style="padding-top:0;">
    <div class="container" style="max-width:860px;">
        @if($update->cover_image_url)
            <div class="gallery-item" style="margin-bottom:1.25rem;">
                <img src="{{ $update->cover_image_url }}" alt="{{ $update->title }}">
            </div>
        @endif
        <div class="panel">
            <div style="white-space:pre-line;color:var(--ink-soft);">{{ $update->body }}</div>
        </div>

        @if($more->isNotEmpty())
            <div style="margin-top:2.5rem;">
                <h2 style="font-family:var(--font-display);margin:0 0 1rem;">More updates</h2>
                <div class="grid-3">
                    @foreach($more as $item)
                        @include('partials.update-card', ['update' => $item])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
