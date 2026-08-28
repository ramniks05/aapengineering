<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $pdo->prepare('DELETE FROM updates WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        flash('success', 'Update deleted.');
        redirect('manage/updates');
    }

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($slug === '') {
        $slug = slugify($title);
    } else {
        $slug = slugify($slug);
    }

    if ($title === '') {
        flash('error', 'Title is required.');
        redirect('manage/updates');
    }

    $publishedAt = trim($_POST['published_at'] ?? '');
    if ($publishedAt === '') {
        $publishedAt = now();
    } else {
        $publishedAt = date('Y-m-d H:i:s', strtotime($publishedAt));
    }

    $data = [
        $title, $slug,
        trim($_POST['excerpt'] ?? '') ?: null,
        trim($_POST['body'] ?? '') ?: null,
        trim($_POST['cover_image_url'] ?? '') ?: null,
        $publishedAt,
        isset($_POST['is_published']) ? 1 : 0,
        now(),
    ];

    $editId = (int) ($_POST['id'] ?? 0);
    if ($editId > 0) {
        $data[] = $editId;
        $pdo->prepare('UPDATE updates SET title=?, slug=?, excerpt=?, body=?, cover_image_url=?, published_at=?, is_published=?, updated_at=? WHERE id=?')->execute($data);
        flash('success', 'Update saved.');
    } else {
        $pdo->prepare('INSERT INTO updates (title, slug, excerpt, body, cover_image_url, published_at, is_published, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?)')
            ->execute([...array_slice($data, 0, 7), now(), now()]);
        flash('success', 'Update created.');
    }
    redirect('manage/updates');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM updates WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$updates = $pdo->query('SELECT * FROM updates ORDER BY published_at DESC, id DESC')->fetchAll();

$pageTitle = 'Updates';
$heading = 'Updates';
$currentAdmin = 'updates';
require __DIR__.'/../includes/layout/admin-header.php';

$u = $edit ?: ['title' => '', 'slug' => '', 'excerpt' => '', 'body' => '', 'cover_image_url' => '', 'published_at' => date('Y-m-d'), 'is_published' => 1];
?>
<div class="split">
    <div class="panel">
        <h2><?= $edit ? 'Edit update' : 'Add update' ?></h2>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id'] ?>"><?php endif; ?>
            <div class="form-field full"><label>Title *</label><input name="title" value="<?= e($u['title']) ?>" required></div>
            <div class="form-field full"><label>Slug</label><input name="slug" value="<?= e($u['slug'] ?? '') ?>"></div>
            <div class="form-field full"><label>Excerpt</label><textarea name="excerpt" rows="2"><?= e($u['excerpt'] ?? '') ?></textarea></div>
            <div class="form-field full"><label>Body</label><textarea name="body" rows="8"><?= e($u['body'] ?? '') ?></textarea></div>
            <div class="form-field full"><label>Cover image URL</label><input name="cover_image_url" value="<?= e($u['cover_image_url'] ?? '') ?>"></div>
            <div class="form-field"><label>Published date</label><input type="date" name="published_at" value="<?= e(substr($u['published_at'] ?? date('Y-m-d'), 0, 10)) ?>"></div>
            <div class="form-field full"><label class="checkbox-row"><input type="checkbox" name="is_published" value="1" <?= ($u['is_published'] ?? 1) ? 'checked' : '' ?>> Published</label></div>
            <div class="form-field full"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Create' ?></button></div>
        </form>
    </div>
    <div class="panel">
        <table class="table">
            <thead><tr><th>Title</th><th>Published</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($updates as $row): ?>
                <tr>
                    <td><?= e($row['title']) ?></td>
                    <td><?= $row['is_published'] ? e(format_date(substr($row['published_at'] ?? '', 0, 10))) : 'Draft' ?></td>
                    <td>
                        <a href="<?= e(url('manage/updates?edit='.$row['id'])) ?>">Edit</a> ·
                        <a href="<?= e(url('update/'.$row['slug'])) ?>" target="_blank">View</a> ·
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
