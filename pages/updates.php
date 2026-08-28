<?php

declare(strict_types=1);

$currentPath = 'updates';
$pageTitle = 'Updates | AAP Engineerings';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

$total = (int) $pdo->query('SELECT COUNT(*) FROM updates WHERE is_published=1')->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));

$updates = $pdo->query("
    SELECT * FROM updates WHERE is_published=1
    ORDER BY published_at DESC, id DESC
    LIMIT {$perPage} OFFSET {$offset}
")->fetchAll();

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Updates</h1>
    <p class="lede">Company news, project milestones and delivery notes.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container grid-3">
        <?php if ($updates): ?>
            <?php foreach ($updates as $update): ?>
                <?php require __DIR__.'/../includes/partials/update-card.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="panel"><p>No updates published yet.</p></div>
        <?php endif; ?>
    </div>
    <?php if ($pages > 1): ?>
    <div class="container pagination">
        <?php if ($page > 1): ?><a href="<?= e(url('updates?page='.($page - 1))) ?>">← Previous</a><?php endif; ?>
        <span>Page <?= $page ?> of <?= $pages ?></span>
        <?php if ($page < $pages): ?><a href="<?= e(url('updates?page='.($page + 1))) ?>">Next →</a><?php endif; ?>
    </div>
    <?php endif; ?>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
