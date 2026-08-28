<?php

declare(strict_types=1);

$currentPath = 'about';
$pageTitle = 'About | AAP Engineerings';

$clients = $pdo->query('SELECT * FROM clients WHERE is_active=1 ORDER BY sort_order, name LIMIT 12')->fetchAll();

require __DIR__.'/../includes/layout/header.php';
?>
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

<?php if ($clients): ?>
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <h2>Trusted by</h2>
            <a href="<?= e(url('clients')) ?>" class="btn btn-secondary">All clients</a>
        </div>
        <div class="grid-4">
            <?php foreach ($clients as $client): ?>
                <div class="client-card panel">
                    <?php if ($client['logo_url']): ?>
                        <img src="<?= e($client['logo_url']) ?>" alt="<?= e($client['name']) ?>" style="max-height:48px;margin-bottom:.8rem;">
                    <?php endif; ?>
                    <strong><?= e($client['name']) ?></strong>
                    <?php if ($client['industry']): ?><p style="color:var(--muted);margin:.3rem 0 0;"><?= e($client['industry']) ?></p><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
