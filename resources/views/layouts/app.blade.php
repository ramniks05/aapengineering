<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AAP Engineerings')</title>
    <meta name="description" content="@yield('meta', 'AAP Engineerings — complete electrical projects, goods and services across cities.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
</head>
<body>
@php
    $waNumber = preg_replace('/\D+/', '', config('company.whatsapp'));
    $waText = rawurlencode(config('company.whatsapp_message'));
    $waLink = 'https://wa.me/'.$waNumber.'?text='.$waText;
@endphp

<header class="site-header">
    <div class="container nav">
        <a href="{{ route('home') }}" class="brand">
            <div class="brand-mark">AAP</div>
            <div>
                <strong>AAP Engineerings</strong>
                <span>Electrical Projects & Services</span>
            </div>
        </a>

        <button class="nav-toggle" type="button" aria-label="Menu" data-nav-toggle>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>

        <nav class="nav-links" data-nav>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
            <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a>
            <a href="{{ route('clients') }}" class="{{ request()->routeIs('clients') ? 'active' : '' }}">Clients</a>
            <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
            <a href="{{ route('updates.index') }}" class="{{ request()->routeIs('updates.*') ? 'active' : '' }}">Updates</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            <a href="{{ route('enquiry') }}" class="btn btn-primary">Enquiry</a>
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <strong>AAP Engineerings</strong>
            <p>Complete electrical projects — goods, installation and commissioning under one accountable team.</p>
            <p style="margin-top:1rem;">
                <a class="btn btn-accent" href="{{ $waLink }}" target="_blank" rel="noopener">Chat on WhatsApp</a>
            </p>
        </div>
        <div>
            <p style="font-weight:700;color:var(--steel);margin:0 0 .7rem;">Explore</p>
            <div class="footer-links">
                <a href="{{ route('projects.index') }}">Projects</a>
                <a href="{{ route('clients') }}">Our clients</a>
                <a href="{{ route('gallery') }}">Gallery</a>
                <a href="{{ route('updates.index') }}">Updates</a>
            </div>
        </div>
        <div>
            <p style="font-weight:700;color:var(--steel);margin:0 0 .7rem;">Contact</p>
            <div class="footer-links">
                <a href="tel:{{ config('company.phone') }}">{{ config('company.phone') }}</a>
                <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a>
                <span>{{ config('company.address') }}</span>
                <a href="{{ route('contact') }}">Contact us</a>
            </div>
            <p style="margin-top:1.2rem;font-size:.9rem;">&copy; {{ date('Y') }} AAP Engineerings</p>
        </div>
    </div>
</footer>

<a class="wa-float" href="{{ $waLink }}" target="_blank" rel="noopener" aria-label="WhatsApp chat">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.3 17.7L2 22l4.4-1.2A11 11 0 1 0 20.5 3.5zm-8.5 17a9 9 0 0 1-4.6-1.3l-.3-.2-2.6.7.7-2.5-.2-.3a9 9 0 1 1 7 3.6zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.5-.6c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3s-1 1-1 2.4 1 2.8 1.2 3a12.4 12.4 0 0 0 4.7 4.1c.7.3 1.1.4 1.5.4s1.2-.1 1.6-1 .8-1.4.9-1.5-.1-.2-.3-.3z"/></svg>
</a>

<div class="lightbox" data-lightbox>
    <button class="lightbox-close" type="button" data-lightbox-close aria-label="Close">&times;</button>
    <div class="lightbox-inner" data-lightbox-inner></div>
</div>

<script>
(() => {
  const toggle = document.querySelector('[data-nav-toggle]');
  const nav = document.querySelector('[data-nav]');
  toggle?.addEventListener('click', () => nav?.classList.toggle('open'));

  const lightbox = document.querySelector('[data-lightbox]');
  const inner = document.querySelector('[data-lightbox-inner]');
  const closeBtn = document.querySelector('[data-lightbox-close]');

  document.querySelectorAll('[data-lightbox-src]').forEach((el) => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const type = el.dataset.lightboxType || 'image';
      const src = el.dataset.lightboxSrc;
      if (!src || !inner || !lightbox) return;
      if (type === 'youtube') {
        inner.innerHTML = `<iframe src="${src}" allowfullscreen allow="autoplay; encrypted-media"></iframe>`;
      } else if (type === 'video') {
        inner.innerHTML = `<video src="${src}" controls autoplay></video>`;
      } else {
        inner.innerHTML = `<img src="${src}" alt="">`;
      }
      lightbox.classList.add('open');
    });
  });

  const close = () => {
    lightbox?.classList.remove('open');
    if (inner) inner.innerHTML = '';
  };
  closeBtn?.addEventListener('click', close);
  lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) close(); });
})();
</script>
</body>
</html>
