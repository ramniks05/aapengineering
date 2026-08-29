<?php

declare(strict_types=1);

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS city_name, c.state AS city_state
    FROM projects p
    LEFT JOIN cities c ON c.id = p.city_id
    WHERE p.slug = ? AND p.is_published = 1
    LIMIT 1
");
$stmt->execute([$slug]);
$project = $stmt->fetch();

if (! $project) {
    http_response_code(404);
    $pageTitle = 'Project not found';
    $currentPath = 'projects';
    require __DIR__.'/../includes/layout/header.php';
    echo '<div class="container page-hero"><h1>Not found</h1></div>';
    require __DIR__.'/../includes/layout/footer.php';
    exit;
}

$mediaStmt = $pdo->prepare('SELECT * FROM project_media WHERE project_id = ? ORDER BY sort_order, id');
$mediaStmt->execute([$project['id']]);
$media = $mediaStmt->fetchAll();

$relatedStmt = $pdo->prepare("
    SELECT p.*, c.name AS city_name
    FROM projects p
    LEFT JOIN cities c ON c.id = p.city_id
    WHERE p.is_published = 1 AND p.id != ? AND (p.city_id <=> ? OR p.status = ?)
    ORDER BY p.is_featured DESC, p.created_at DESC
    LIMIT 3
");
$relatedStmt->execute([$project['id'], $project['city_id'], $project['status']]);
$related = $relatedStmt->fetchAll();

$currentPath = 'projects';
$pageTitle = $project['title'].' | '.$config['app_name'];
$pageMeta = $project['short_description'] ?? '';
require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <div class="meta" style="margin-bottom:1rem;">
        <span class="badge <?= e($project['status']) ?>"><?= e(status_label($project['status'])) ?></span>
        <?php if ($project['city_name']): ?>
            <span class="badge"><?= e($project['city_name'].($project['city_state'] ? ', '.$project['city_state'] : '')) ?></span>
        <?php endif; ?>
        <?php if ($project['project_type']): ?>
            <span class="badge"><?= e($project['project_type']) ?></span>
        <?php endif; ?>
    </div>
    <h1><?= e($project['title']) ?></h1>
    <p class="lede"><?= e($project['short_description']) ?></p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container split">
        <div>
            <?php if ($project['cover_image_url']): ?>
                <div class="gallery-item" style="margin-bottom:1rem;">
                    <img src="<?= e($project['cover_image_url']) ?>" alt="<?= e($project['title']) ?>">
                </div>
            <?php endif; ?>

            <div class="panel" style="margin-bottom:1.25rem;">
                <h2>Project overview</h2>
                <div style="white-space:pre-line;color:var(--muted);"><?= e($project['description']) ?></div>
            </div>

            <h2 style="font-family:var(--font-display);margin:0 0 1rem;">Gallery</h2>
            <div class="gallery">
                <?php if ($media): ?>
                    <?php foreach ($media as $m): ?>
                        <div class="gallery-item">
                            <?php if ($m['type'] === 'image'): ?>
                                <img src="<?= e($m['url']) ?>" alt="<?= e($m['caption'] ?: $project['title']) ?>">
                            <?php elseif ($m['type'] === 'video_youtube' && youtube_embed($m['url'])): ?>
                                <iframe src="<?= e(youtube_embed($m['url'])) ?>" title="<?= e($m['caption'] ?: $project['title']) ?>" allowfullscreen loading="lazy"></iframe>
                            <?php elseif ($m['type'] === 'video_cdn'): ?>
                                <video controls preload="metadata" poster="<?= e($m['thumbnail_url'] ?? '') ?>">
                                    <source src="<?= e($m['url']) ?>">
                                </video>
                            <?php endif; ?>
                            <?php if ($m['caption']): ?><div class="caption"><?= e($m['caption']) ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="panel">Media will appear here when added from admin.</div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="panel">
            <h2>Details</h2>
            <p><strong>Status:</strong> <?= e(status_label($project['status'])) ?></p>
            <?php if ($project['client_name']): ?><p><strong>Client:</strong> <?= e($project['client_name']) ?></p><?php endif; ?>
            <?php if ($project['city_name']): ?>
                <p><strong>Location:</strong> <?= e($project['city_name'].($project['city_state'] ? ', '.$project['city_state'] : '')) ?></p>
            <?php endif; ?>
            <?php if ($project['start_date']): ?><p><strong>Start:</strong> <?= e(format_date($project['start_date'])) ?></p><?php endif; ?>
            <?php if ($project['end_date']): ?><p><strong>End:</strong> <?= e(format_date($project['end_date'])) ?></p><?php endif; ?>
            <a href="<?= e(url('enquiry?interest='.rawurlencode($project['title']))) ?>" class="btn btn-primary" style="width:100%;margin-top:1rem;">Enquire about similar work</a>
        </aside>
    </div>

    <?php if ($related): ?>
        <div class="container" style="margin-top:3rem;">
            <div class="section-head"><h2>Related projects</h2></div>
            <div class="grid-3">
                <?php foreach ($related as $project): ?>
                    <?php require __DIR__.'/../includes/partials/project-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
