@extends('layouts.app')

@section('title', 'AAP Engineerings | Complete Electrical Projects')

@section('content')
<section class="hero">
    <div class="hero-media" aria-hidden="true"></div>
    <div class="container hero-content">
        <div class="eyebrow">Full project electrical delivery</div>
        <h1>AAP Engineerings</h1>
        <p>Industrial, commercial and institutional electrical works — complete project ownership from supply to commissioning.</p>
        <div class="hero-actions">
            <a href="{{ route('projects.index') }}" class="btn btn-primary">Explore projects</a>
            <a href="{{ route('contact') }}" class="btn btn-ghost">Contact us</a>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container">
        <div class="stats">
            <div class="stat"><strong>{{ $stats['completed'] }}</strong><span>Completed projects</span></div>
            <div class="stat"><strong>{{ $stats['ongoing'] }}</strong><span>Ongoing projects</span></div>
            <div class="stat"><strong>{{ $stats['upcoming'] }}</strong><span>Upcoming projects</span></div>
            <div class="stat"><strong>{{ $stats['clients'] }}</strong><span>Trusted clients</span></div>
        </div>
    </div>
</section>

@if($clients->isNotEmpty())
<section class="section" style="padding-top:1.5rem;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Our clients</h2>
                <p class="lede">Partners who trust AAP Engineerings for complete electrical execution.</p>
            </div>
            <a href="{{ route('clients') }}" class="btn btn-secondary">View all</a>
        </div>
        <div class="clients-marquee">
            <div class="clients-track">
                @foreach([$clients, $clients] as $loopClients)
                    @foreach($loopClients as $client)
                        <div class="chip">
                            @if($client->logo_url)
                                <img src="{{ $client->logo_url }}" alt="{{ $client->name }}">
                            @endif
                            <div>
                                <strong>{{ $client->name }}</strong>
                                @if($client->industry)<div style="color:var(--muted);font-size:.82rem;">{{ $client->industry }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="section" style="padding-top:1rem;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Featured projects</h2>
                <p class="lede">Upcoming, ongoing and completed works — filter by city and status anytime.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">All projects</a>
        </div>
        <div class="grid-3">
            @forelse($featured as $project)
                @include('partials.project-card', ['project' => $project])
            @empty
                <div class="panel"><p>Projects will appear here once published.</p></div>
            @endforelse
        </div>
    </div>
</section>

@if($gallery->isNotEmpty())
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Gallery</h2>
                <p class="lede">Site visuals, commissioning moments and project documentation.</p>
            </div>
            <a href="{{ route('gallery') }}" class="btn btn-secondary">Open gallery</a>
        </div>
        <div class="gallery-masonry">
            @foreach($gallery as $item)
                @include('partials.gallery-tile', ['item' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($updates->isNotEmpty())
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Latest updates</h2>
                <p class="lede">Company news, project milestones and delivery notes.</p>
            </div>
            <a href="{{ route('updates.index') }}" class="btn btn-secondary">All updates</a>
        </div>
        <div class="grid-3">
            @foreach($updates as $update)
                @include('partials.update-card', ['update' => $update])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="band">
            <div class="split" style="position:relative;z-index:1;">
                <div>
                    <h2 style="font-family:var(--font-display);font-size:clamp(1.8rem,3vw,2.5rem);margin:0 0 .8rem;">Ready to start your electrical project?</h2>
                    <p style="margin:0;color:rgba(255,255,255,.82);max-width:34rem;">Share your requirement — we handle complete scopes across cities with clear project tracking.</p>
                </div>
                <div style="display:grid;gap:.75rem;align-content:center;">
                    <a href="{{ route('enquiry') }}" class="btn btn-primary" style="width:100%;">Send enquiry</a>
                    <a href="{{ route('contact') }}" class="btn btn-ghost" style="width:100%;">Contact details</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
