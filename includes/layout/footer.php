</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <strong><?= e($appName) ?></strong>
            <p>Complete electrical projects — goods, installation and commissioning under one accountable team.</p>
            <p style="margin-top:1rem;">
                <a class="btn btn-accent" href="<?= e($waLink) ?>" target="_blank" rel="noopener">Chat on WhatsApp</a>
            </p>
        </div>
        <div>
            <p style="font-weight:700;color:var(--steel);margin:0 0 .7rem;">Explore</p>
            <div class="footer-links">
                <a href="<?= e(url('projects')) ?>">Projects</a>
                <a href="<?= e(url('clients')) ?>">Our clients</a>
                <a href="<?= e(url('gallery')) ?>">Gallery</a>
                <a href="<?= e(url('updates')) ?>">Updates</a>
            </div>
        </div>
        <div>
            <p style="font-weight:700;color:var(--steel);margin:0 0 .7rem;">Contact</p>
            <div class="footer-links">
                <?php foreach (company_phones($company) as $phone): ?>
                    <a href="<?= e(phone_href($phone)) ?>"><?= e($phone) ?></a>
                <?php endforeach; ?>
                <?php foreach (company_emails($company) as $email): ?>
                    <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                <?php endforeach; ?>
                <span><?= e($company['address']) ?></span>
            </div>
            <p style="margin-top:1.2rem;font-size:.9rem;">&copy; <?= date('Y') ?> <?= e($appName) ?></p>
        </div>
    </div>
</footer>
<a class="wa-float" href="<?= e($waLink) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.3 17.7L2 22l4.4-1.2A11 11 0 1 0 20.5 3.5zm-8.5 17a9 9 0 0 1-4.6-1.3l-.3-.2-2.6.7.7-2.5-.2-.3a9 9 0 1 1 7 3.6zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.5-.6c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3s-1 1-1 2.4 1 2.8 1.2 3a12.4 12.4 0 0 0 4.7 4.1c.7.3 1.1.4 1.5.4s1.2-.1 1.6-1 .8-1.4.9-1.5-.1-.2-.3-.3z"/></svg>
</a>
<div class="lightbox" data-lightbox>
    <button class="lightbox-close" type="button" data-lightbox-close aria-label="Close">&times;</button>
    <div class="lightbox-inner" data-lightbox-inner></div>
</div>
<script>
(() => {
  document.querySelector('[data-nav-toggle]')?.addEventListener('click', () => document.querySelector('[data-nav]')?.classList.toggle('open'));
  const lightbox = document.querySelector('[data-lightbox]');
  const inner = document.querySelector('[data-lightbox-inner]');
  document.querySelectorAll('[data-lightbox-src]').forEach(el => {
    el.addEventListener('click', e => {
      e.preventDefault();
      const type = el.dataset.lightboxType || 'image';
      const src = el.dataset.lightboxSrc;
      if (!src || !inner || !lightbox) return;
      if (type === 'youtube') inner.innerHTML = `<iframe src="${src}" allowfullscreen></iframe>`;
      else if (type === 'video') inner.innerHTML = `<video src="${src}" controls autoplay></video>`;
      else inner.innerHTML = `<img src="${src}" alt="">`;
      lightbox.classList.add('open');
    });
  });
  const close = () => { lightbox?.classList.remove('open'); if (inner) inner.innerHTML = ''; };
  document.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
  lightbox?.addEventListener('click', e => { if (e.target === lightbox) close(); });
})();
</script>
</body>
</html>
