<?php
/** @var array $project */
$cover = $project['cover_image_url'] ?: 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=1000&q=80';
?>
<a href="<?= e(url('project/'.$project['slug'])) ?>" class="project-card">
    <div class="thumb" style="background-image:url('<?= e($cover) ?>')"></div>
    <div class="body">
        <div class="meta">
            <span class="badge <?= e($project['status']) ?>"><?= e(status_label($project['status'])) ?></span>
            <?php if (! empty($project['city_name'])): ?>
                <span class="badge"><?= e($project['city_name']) ?></span>
            <?php endif; ?>
        </div>
        <h3><?= e($project['title']) ?></h3>
        <p><?= e(excerpt($project['short_description'] ?? '', 110)) ?></p>
    </div>
</a>
