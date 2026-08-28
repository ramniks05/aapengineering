@extends('layouts.app')

@section('title', 'Our Clients | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>Our clients</h1>
    <p class="lede">Organizations that partner with AAP Engineerings for complete electrical project delivery.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="grid-4">
            @forelse($clients as $client)
                <div class="client-card">
                    @if($client->logo_url)
                        <img src="{{ $client->logo_url }}" alt="{{ $client->name }}">
                    @else
                        <div class="brand-mark" style="min-width:72px;height:72px;font-size:1rem;padding:0 0.75rem;">{{ strtoupper(substr($client->name,0,2)) }}</div>
                    @endif
                    <div>
                        <strong>{{ $client->name }}</strong>
                        @if($client->industry)<div><span>{{ $client->industry }}</span></div>@endif
                        @if($client->website_url)
                            <div style="margin-top:.4rem;"><a href="{{ $client->website_url }}" target="_blank" rel="noopener" style="color:var(--brand-2);font-size:.88rem;">Website</a></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="panel" style="grid-column:1/-1;"><p>Clients will appear here once added from admin.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
