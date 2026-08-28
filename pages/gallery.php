<?php

declare(strict_types=1);

$currentPath = 'gallery';
$pageTitle = 'Gallery | AAP Engineerings';
$items = $pdo->query('SELECT * FROM gallery_items WHERE is_active=1 ORDER BY sort_order, id DESC')->fetchAll();

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Gallery</h1>
    <p class="lede">Site photos, commissioning moments and project documentation.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container gallery-masonry">
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <?php require __DIR__.'/../includes/partials/gallery-tile.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="panel"><p>Gallery items will appear here once published.</p></div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
