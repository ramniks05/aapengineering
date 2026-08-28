<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $pdo->prepare('DELETE FROM gallery_items WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        flash('success', 'Gallery item deleted.');
        redirect('panel/gallery');
    }

    $url = trim($_POST['url'] ?? '');
    if ($url === '') {
        flash('error', 'URL is required.');
        redirect('panel/gallery');
    }

    $data = [
        trim($_POST['title'] ?? '') ?: null,
        $_POST['type'] ?? 'image',
        $url,
        trim($_POST['thumbnail_url'] ?? '') ?: null,
        trim($_POST['category'] ?? '') ?: null,
        trim($_POST['caption'] ?? '') ?: null,
        (int) ($_POST['sort_order'] ?? 0),
        isset($_POST['is_active']) ? 1 : 0,
        now(),
    ];

    $editId = (int) ($_POST['id'] ?? 0);
    if ($editId > 0) {
        $data[] = $editId;
        $pdo->prepare('UPDATE gallery_items SET title=?, type=?, url=?, thumbnail_url=?, category=?, caption=?, sort_order=?, is_active=?, updated_at=? WHERE id=?')->execute($data);
        flash('success', 'Gallery item updated.');
    } else {
        $pdo->prepare('INSERT INTO gallery_items (title, type, url, thumbnail_url, category, caption, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)')
            ->execute([...array_slice($data, 0, 8), now(), now()]);
        flash('success', 'Gallery item added.');
    }
    redirect('panel/gallery');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM gallery_items WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$items = $pdo->query('SELECT * FROM gallery_items ORDER BY sort_order, id DESC')->fetchAll();

$pageTitle = 'Gallery';
$heading = 'Gallery';
$currentAdmin = 'gallery';
require __DIR__.'/../includes/layout/admin-header.php';

$g = $edit ?: ['title' => '', 'type' => 'image', 'url' => '', 'thumbnail_url' => '', 'category' => '', 'caption' => '', 'sort_order' => 0, 'is_active' => 1];
?>
<div class="split">
    <div class="panel">
        <h2><?= $edit ? 'Edit item' : 'Add item' ?></h2>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id'] ?>"><?php endif; ?>
            <div class="form-field full"><label>Title</label><input name="title" value="<?= e($g['title'] ?? '') ?>"></div>
            <div class="form-field">
                <label>Type</label>
                <select name="type">
                    <option value="image" <?= ($g['type'] ?? '') === 'image' ? 'selected' : '' ?>>Image</option>
                    <option value="video_cdn" <?= ($g['type'] ?? '') === 'video_cdn' ? 'selected' : '' ?>>Video CDN</option>
                    <option value="video_youtube" <?= ($g['type'] ?? '') === 'video_youtube' ? 'selected' : '' ?>>YouTube</option>
                </select>
            </div>
            <div class="form-field full"><label>URL *</label><input name="url" value="<?= e($g['url'] ?? '') ?>" required></div>
            <div class="form-field full"><label>Thumbnail URL</label><input name="thumbnail_url" value="<?= e($g['thumbnail_url'] ?? '') ?>"></div>
            <div class="form-field"><label>Category</label><input name="category" value="<?= e($g['category'] ?? '') ?>"></div>
            <div class="form-field"><label>Caption</label><input name="caption" value="<?= e($g['caption'] ?? '') ?>"></div>
            <div class="form-field"><label>Sort order</label><input type="number" name="sort_order" value="<?= (int) ($g['sort_order'] ?? 0) ?>"></div>
            <div class="form-field full"><label class="checkbox-row"><input type="checkbox" name="is_active" value="1" <?= ($g['is_active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            <div class="form-field full"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add' ?></button></div>
        </form>
    </div>
    <div class="panel">
        <table class="table">
            <thead><tr><th>Title</th><th>Type</th><th>Active</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($items as $row): ?>
                <tr>
                    <td><?= e($row['title'] ?: excerpt($row['url'], 40)) ?></td>
                    <td><?= e($row['type']) ?></td>
                    <td><?= $row['is_active'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="<?= e(url('panel/gallery?edit='.$row['id'])) ?>">Edit</a> ·
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                            <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__.'/../includes/layout/admin-footer.php'; ?>
