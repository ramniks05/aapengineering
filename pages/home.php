<?php

declare(strict_types=1);

$currentPath = '';

$stats = [
    'completed' => (int) $pdo->query("SELECT COUNT(*) FROM projects WHERE status='completed' AND is_published=1")->fetchColumn(),
    'ongoing' => (int) $pdo->query("SELECT COUNT(*) FROM projects WHERE status='ongoing' AND is_published=1")->fetchColumn(),
    'upcoming' => (int) $pdo->query("SELECT COUNT(*) FROM projects WHERE status='upcoming' AND is_published=1")->fetchColumn(),
    'clients' => (int) $pdo->query('SELECT COUNT(*) FROM clients WHERE is_active=1')->fetchColumn(),
];

$clients = $pdo->query('SELECT * FROM clients WHERE is_active=1 ORDER BY sort_order, name LIMIT 8')->fetchAll();

$featured = $pdo->query("
    SELECT p.*, c.name AS city_name
    FROM projects p
    LEFT JOIN cities c ON c.id = p.city_id
    WHERE p.is_published=1 AND p.is_featured=1
    ORDER BY p.sort_order, p.created_at DESC
    LIMIT 6
")->fetchAll();

$gallery = $pdo->query('SELECT * FROM gallery_items WHERE is_active=1 ORDER BY sort_order, id DESC LIMIT 6')->fetchAll();

$updates = $pdo->query("
    SELECT * FROM updates
    WHERE is_published=1
    ORDER BY published_at DESC, id DESC
    LIMIT 3
")->fetchAll();

$pageTitle = $config['app_name'].' | Complete Electrical Projects';
require __DIR__.'/../includes/layout/header.php';
?>
<section class="hero">
    <div class="hero-media" aria-hidden="true"></div>
    <div class="container hero-content">
        <div class="eyebrow">Full project electrical delivery</div>
        <h1><?= e($config['app_name']) ?></h1>
        <p>Industrial, commercial and institutional electrical works — complete project ownership from supply to commissioning.</p>
        <div class="hero-actions">
            <a href="<?= e(url('projects')) ?>" class="btn btn-primary">Explore projects</a>
            <a href="<?= e(url('contact')) ?>" class="btn btn-ghost">Contact us</a>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container">
        <div class="stats">
            <div class="stat"><strong><?= $stats['completed'] ?></strong><span>Completed projects</span></div>
            <div class="stat"><strong><?= $stats['ongoing'] ?></strong><span>Ongoing projects</span></div>
            <div class="stat"><strong><?= $stats['upcoming'] ?></strong><span>Upcoming projects</span></div>
            <div class="stat"><strong><?= $stats['clients'] ?></strong><span>Trusted clients</span></div>
        </div>
    </div>
</section>

<?php if ($clients): ?>
<section class="section" style="padding-top:1.5rem;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Our clients</h2>
                <p class="lede">Partners who trust <?= e($config['app_name']) ?> for complete electrical execution.</p>
            </div>
            <a href="<?= e(url('clients')) ?>" class="btn btn-secondary">View all</a>
        </div>
        <div class="clients-marquee">
            <div class="clients-track">
                <?php foreach ([$clients, $clients] as $loopClients): ?>
                    <?php foreach ($loopClients as $client): ?>
                        <div class="chip">
                            <?php if ($client['logo_url']): ?>
                                <img class="client-logo client-logo--sm" src="<?= e($client['logo_url']) ?>" alt="<?= e($client['name']) ?>">
                            <?php endif; ?>
                            <div>
                                <strong><?= e($client['name']) ?></strong>
                                <?php if ($client['industry']): ?><div style="color:var(--muted);font-size:.82rem;"><?= e($client['industry']) ?></div><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section" style="padding-top:1rem;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Featured projects</h2>
                <p class="lede">Upcoming, ongoing and completed works — filter by city and status anytime.</p>
            </div>
            <a href="<?= e(url('projects')) ?>" class="btn btn-secondary">All projects</a>
        </div>
        <div class="grid-3">
            <?php if ($featured): ?>
                <?php foreach ($featured as $project): ?>
                    <?php require __DIR__.'/../includes/partials/project-card.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="panel"><p>Projects will appear here once published.</p></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($gallery): ?>
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Gallery</h2>
                <p class="lede">Site visuals, commissioning moments and project documentation.</p>
            </div>
            <a href="<?= e(url('gallery')) ?>" class="btn btn-secondary">Open gallery</a>
        </div>
        <div class="gallery-masonry">
            <?php foreach ($gallery as $item): ?>
                <?php require __DIR__.'/../includes/partials/gallery-tile.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($updates): ?>
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Latest updates</h2>
                <p class="lede">Company news, project milestones and delivery notes.</p>
            </div>
            <a href="<?= e(url('updates')) ?>" class="btn btn-secondary">All updates</a>
        </div>
        <div class="grid-3">
            <?php foreach ($updates as $update): ?>
                <?php require __DIR__.'/../includes/partials/update-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="band">
            <div class="split" style="position:relative;z-index:1;">
                <div>
                    <h2 style="font-family:var(--font-display);font-size:clamp(1.8rem,3vw,2.5rem);margin:0 0 .8rem;">Ready to start your electrical project?</h2>
                    <p style="margin:0;color:rgba(255,255,255,.82);max-width:34rem;">Share your requirement — we handle complete scopes across cities with clear project tracking.</p>
                </div>
                <div style="display:grid;gap:.75rem;align-content:center;">
                    <a href="<?= e(url('enquiry')) ?>" class="btn btn-primary" style="width:100%;">Send enquiry</a>
                    <a href="<?= e(url('contact')) ?>" class="btn btn-ghost" style="width:100%;">Contact details</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
