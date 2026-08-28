<?php
/** @var array $update */
$cover = $update['cover_image_url'] ?: 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=800&q=80';
?>
<a href="<?= e(url('update/'.$update['slug'])) ?>" class="update-card">
    <div class="thumb" style="background-image:url('<?= e($cover) ?>')"></div>
    <div class="body">
        <div class="meta"><span class="badge"><?= e(format_date(substr($update['published_at'] ?? '', 0, 10))) ?></span></div>
        <h3><?= e($update['title']) ?></h3>
        <p><?= e(excerpt($update['excerpt'] ?? $update['body'] ?? '', 120)) ?></p>
    </div>
</a>
