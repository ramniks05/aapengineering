<?php

declare(strict_types=1);

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM updates WHERE slug = ? AND is_published = 1 LIMIT 1');
$stmt->execute([$slug]);
$update = $stmt->fetch();

if (! $update) {
    http_response_code(404);
    $currentPath = 'updates';
    $pageTitle = 'Update not found';
    require __DIR__.'/../includes/layout/header.php';
    echo '<div class="container page-hero"><h1>Not found</h1></div>';
    require __DIR__.'/../includes/layout/footer.php';
    exit;
}

$currentPath = 'updates';
$pageTitle = $update['title'].' | AAP Engineerings';
require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <div class="meta" style="margin-bottom:1rem;">
        <span class="badge"><?= e(format_date(substr($update['published_at'] ?? '', 0, 10))) ?></span>
    </div>
    <h1><?= e($update['title']) ?></h1>
    <?php if ($update['excerpt']): ?><p class="lede"><?= e($update['excerpt']) ?></p><?php endif; ?>
</div>

<section class="section" style="padding-top:0;">
    <div class="container" style="max-width:820px;">
        <?php if ($update['cover_image_url']): ?>
            <img src="<?= e($update['cover_image_url']) ?>" alt="<?= e($update['title']) ?>" style="border-radius:var(--radius);margin-bottom:1.5rem;">
        <?php endif; ?>
        <div class="panel" style="white-space:pre-line;"><?= e($update['body']) ?></div>
        <p style="margin-top:1.5rem;"><a href="<?= e(url('updates')) ?>">← All updates</a></p>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
