@extends('layouts.app')

@section('title', 'Contact Us | AAP Engineerings')

@section('content')
@php
    $waNumber = preg_replace('/\D+/', '', config('company.whatsapp'));
    $waText = rawurlencode(config('company.whatsapp_message'));
    $waLink = 'https://wa.me/'.$waNumber.'?text='.$waText;
@endphp

<div class="container page-hero">
    <h1>Contact us</h1>
    <p class="lede">Dummy office details for demo — replace with your real address, phone and map pin anytime.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container contact-grid">
        <div class="panel contact-info">
            <h2>Get in touch</h2>

            <p style="margin-top:.9rem;">Phone</p>
            <p><a href="tel:{{ preg_replace('/\s+/', '', config('company.phone')) }}">{{ config('company.phone') }}</a></p>

            <p>WhatsApp</p>
            <p><a href="{{ $waLink }}" target="_blank" rel="noopener">+{{ config('company.whatsapp') }}</a></p>

            <p>Email</p>
            <p>
                <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a><br>
                <a href="mailto:{{ config('company.support_email') }}">{{ config('company.support_email') }}</a>
            </p>

            <p>Office address</p>
            <p>{{ config('company.address') }}</p>

            <p>Working hours</p>
            <p>{{ config('company.hours') }}</p>

            <div style="display:grid;gap:.7rem;margin-top:1.2rem;">
                <a class="btn btn-primary" href="{{ $waLink }}" target="_blank" rel="noopener">WhatsApp us</a>
                <a class="btn btn-secondary" href="{{ config('company.map_link') }}" target="_blank" rel="noopener">Open in Google Maps</a>
                <a class="btn btn-secondary" href="{{ route('enquiry') }}">Project enquiry form</a>
            </div>
        </div>

        <div class="panel">
            <h2>Send a message</h2>
            @if(session('success'))
                <div class="alert alert-success" style="margin-top:1rem;">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error" style="margin-top:1rem;">
                    <ul style="margin:0;padding-left:1.1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="form-grid" style="margin-top:1rem;">
                @csrf
                <div class="form-field">
                    <label for="name">Name *</label>
                    <input id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label for="phone">Phone *</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-field">
                    <label for="city">City</label>
                    <input id="city" name="city" value="{{ old('city') }}">
                </div>
                <div class="form-field full">
                    <label for="project_interest">Project interest</label>
                    <input id="project_interest" name="project_interest" value="{{ old('project_interest') }}">
                </div>
                <div class="form-field full">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                </div>
                <div class="form-field full">
                    <button class="btn btn-primary" type="submit">Send message</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Find us on map</h2>
                <p class="lede">{{ config('company.address') }}</p>
            </div>
            <a class="btn btn-secondary" href="{{ config('company.map_link') }}" target="_blank" rel="noopener">Directions</a>
        </div>
        <div class="map-frame">
            <iframe
                title="AAP Engineerings office location"
                src="{{ config('company.map_embed_url') }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>
@endsection
