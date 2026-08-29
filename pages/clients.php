<?php

declare(strict_types=1);

$currentPath = 'clients';
$pageTitle = 'Clients | '.$config['app_name'];
$clients = $pdo->query('SELECT * FROM clients WHERE is_active=1 ORDER BY sort_order, name')->fetchAll();

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Our clients</h1>
    <p class="lede">Organizations that trust <?= e($config['app_name']) ?> for complete electrical project delivery.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container grid-3">
        <?php if ($clients): ?>
            <?php foreach ($clients as $client): ?>
                <div class="client-card panel">
                    <?php if ($client['logo_url']): ?>
                        <img class="client-logo" src="<?= e($client['logo_url']) ?>" alt="<?= e($client['name']) ?>">
                    <?php endif; ?>
                    <strong><?= e($client['name']) ?></strong>
                    <?php if ($client['industry']): ?><p style="color:var(--muted);margin:.4rem 0 0;"><?= e($client['industry']) ?></p><?php endif; ?>
                    <?php if ($client['website_url']): ?>
                        <p style="margin-top:.8rem;"><a href="<?= e($client['website_url']) ?>" target="_blank" rel="noopener">Website →</a></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="panel"><p>Client logos will appear here once added.</p></div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
