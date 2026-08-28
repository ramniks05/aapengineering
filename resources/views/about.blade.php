@extends('layouts.app')

@section('title', 'About | AAP Engineerings')

@section('content')
<div class="container page-hero">
    <h1>About AAP Engineerings</h1>
    <p class="lede">We deliver complete electrical projects — goods, installation and services — for clients who need reliable end-to-end execution.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container split">
        <div class="panel">
            <h2>Who we are</h2>
            <p>AAP Engineerings focuses on full electrical project delivery for industrial plants, commercial buildings, warehouses, healthcare and institutional facilities.</p>
            <p>Unlike component-only suppliers, we take ownership of the project lifecycle: planning coordination, material supply, installation, testing and commissioning.</p>
        </div>
        <div class="panel">
            <h2>How we work</h2>
            <div class="service-list">
                <div class="service-item">
                    <h3>Plan</h3>
                    <p>Scope definition, city-wise project planning and execution roadmap.</p>
                </div>
                <div class="service-item">
                    <h3>Execute</h3>
                    <p>On-site electrical works with quality checks and progress tracking.</p>
                </div>
                <div class="service-item">
                    <h3>Handover</h3>
                    <p>Testing, documentation and clean project close-out.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($clients) && $clients->isNotEmpty())
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Trusted by</h2>
                <p class="lede">A selection of clients we work with.</p>
            </div>
            <a href="{{ route('clients') }}" class="btn btn-secondary">All clients</a>
        </div>
        <div class="grid-4">
            @foreach($clients->take(8) as $client)
                <div class="client-card">
                    @if($client->logo_url)
                        <img src="{{ $client->logo_url }}" alt="{{ $client->name }}">
                    @endif
                    <strong>{{ $client->name }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
