<?php
/** @var array $item */
$preview = media_preview_url($item);
$lightbox = media_lightbox($item);
$label = $item['title'] ?: ($item['category'] ?? '');
?>
<a href="#"
   class="gallery-tile"
   data-lightbox-src="<?= e($lightbox['src']) ?>"
   data-lightbox-type="<?= e($lightbox['type']) ?>">
    <div class="gallery-media">
        <?php if ($preview): ?>
            <img src="<?= e($preview) ?>" alt="<?= e($label ?: 'Gallery item') ?>">
        <?php elseif (($item['type'] ?? '') === 'video_cdn'): ?>
            <video src="<?= e($item['url']) ?>" muted></video>
        <?php else: ?>
            <div class="gallery-fallback">Media</div>
        <?php endif; ?>
        <?php if (($item['type'] ?? 'image') !== 'image'): ?>
            <div class="play"><span>▶</span></div>
        <?php endif; ?>
    </div>
    <?php if ($label): ?>
        <div class="gallery-caption">
            <strong><?= e($label) ?></strong>
            <?php if (! empty($item['category']) && ! empty($item['title'])): ?>
                <span><?= e($item['category']) ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</a>
