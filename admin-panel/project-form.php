<?php

declare(strict_types=1);

require_admin();

$id = (int) ($_GET['id'] ?? 0);
$project = null;
$media = [];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if (! $project) {
        flash('error', 'Project not found.');
        redirect('panel/projects');
    }
    $m = $pdo->prepare('SELECT * FROM project_media WHERE project_id = ? ORDER BY sort_order, id');
    $m->execute([$id]);
    $media = $m->fetchAll();
}

$cities = $pdo->query('SELECT * FROM cities ORDER BY name')->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? 'save';

    if ($action === 'delete_media') {
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $pdo->prepare('DELETE FROM project_media WHERE id = ? AND project_id = ?')->execute([$mediaId, $id]);
        flash('success', 'Media removed.');
        redirect('panel/project?id='.$id);
    }

    if ($action === 'add_media' && $id > 0) {
        $type = $_POST['type'] ?? 'image';
        $url = trim($_POST['url'] ?? '');
        if ($url === '') {
            $errors[] = 'Media URL is required.';
        } else {
            $pdo->prepare('INSERT INTO project_media (project_id, type, url, thumbnail_url, caption, sort_order, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)')
                ->execute([$id, $type, $url, trim($_POST['thumbnail_url'] ?? '') ?: null, trim($_POST['caption'] ?? '') ?: null, (int) ($_POST['sort_order'] ?? 0), now(), now()]);
            flash('success', 'Media added.');
            redirect('panel/project?id='.$id);
        }
    }

    if ($action === 'save') {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '') {
            $slug = slugify($title);
        } else {
            $slug = slugify($slug);
        }
        $status = $_POST['status'] ?? 'upcoming';
        $cityId = $_POST['city_id'] ?? '';
        $cityId = ($cityId !== '' && ctype_digit((string) $cityId)) ? (int) $cityId : null;

        if ($title === '') {
            $errors[] = 'Title is required.';
        }

        if (! $errors) {
            $data = [
                $title, $slug, $status, $cityId,
                trim($_POST['client_name'] ?? '') ?: null,
                trim($_POST['project_type'] ?? '') ?: null,
                trim($_POST['short_description'] ?? '') ?: null,
                trim($_POST['description'] ?? '') ?: null,
                trim($_POST['cover_image_url'] ?? '') ?: null,
                $_POST['start_date'] ?: null,
                $_POST['end_date'] ?: null,
                isset($_POST['is_featured']) ? 1 : 0,
                isset($_POST['is_published']) ? 1 : 0,
                (int) ($_POST['sort_order'] ?? 0),
                now(),
            ];

            if ($project) {
                $data[] = $id;
                $pdo->prepare('UPDATE projects SET title=?, slug=?, status=?, city_id=?, client_name=?, project_type=?, short_description=?, description=?, cover_image_url=?, start_date=?, end_date=?, is_featured=?, is_published=?, sort_order=?, updated_at=? WHERE id=?')
                    ->execute($data);
                flash('success', 'Project updated.');
                redirect('panel/project?id='.$id);
            }

            $pdo->prepare('INSERT INTO projects (title, slug, status, city_id, client_name, project_type, short_description, description, cover_image_url, start_date, end_date, is_featured, is_published, sort_order, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([...array_slice($data, 0, 14), now(), now()]);
            $newId = (int) $pdo->lastInsertId();
            flash('success', 'Project created. You can now add media.');
            redirect('panel/project?id='.$newId);
        }
    }
}

$p = $project ?: [
    'title' => '', 'slug' => '', 'status' => 'upcoming', 'city_id' => null,
    'client_name' => '', 'project_type' => '', 'short_description' => '', 'description' => '',
    'cover_image_url' => '', 'start_date' => '', 'end_date' => '', 'is_featured' => 0,
    'is_published' => 1, 'sort_order' => 0,
];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save' && $errors) {
    $p = array_merge($p, $_POST);
}

$pageTitle = $project ? 'Edit project' : 'Add project';
$heading = $pageTitle;
$currentAdmin = 'projects';
$adminActions = '<a href="'.e(url('panel/projects')).'" class="btn btn-secondary">Back</a>';
require __DIR__.'/../includes/layout/admin-header.php';
?>
<div class="split">
    <div class="panel">
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save">
            <div class="form-field full"><label>Title *</label><input name="title" value="<?= e($p['title']) ?>" required></div>
            <div class="form-field"><label>Slug</label><input name="slug" value="<?= e($p['slug']) ?>" placeholder="auto-generated if empty"></div>
            <div class="form-field">
                <label>Status *</label>
                <select name="status" required>
                    <?php foreach (['upcoming', 'ongoing', 'completed'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= ($p['status'] ?? '') === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field">
                <label>City</label>
                <select name="city_id">
                    <option value="">— Select —</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= (int) $city['id'] ?>" <?= (string) ($p['city_id'] ?? '') === (string) $city['id'] ? 'selected' : '' ?>><?= e($city['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field"><label>Client name</label><input name="client_name" value="<?= e($p['client_name'] ?? '') ?>"></div>
            <div class="form-field"><label>Project type</label><input name="project_type" value="<?= e($p['project_type'] ?? '') ?>"></div>
            <div class="form-field"><label>Start date</label><input type="date" name="start_date" value="<?= e($p['start_date'] ?? '') ?>"></div>
            <div class="form-field"><label>End date</label><input type="date" name="end_date" value="<?= e($p['end_date'] ?? '') ?>"></div>
            <div class="form-field full"><label>Cover image URL (CDN)</label><input name="cover_image_url" value="<?= e($p['cover_image_url'] ?? '') ?>"></div>
            <div class="form-field full"><label>Short description</label><textarea name="short_description" rows="3"><?= e($p['short_description'] ?? '') ?></textarea></div>
            <div class="form-field full"><label>Full description</label><textarea name="description" rows="8"><?= e($p['description'] ?? '') ?></textarea></div>
            <div class="form-field"><label>Sort order</label><input type="number" name="sort_order" value="<?= (int) ($p['sort_order'] ?? 0) ?>"></div>
            <div class="form-field" style="justify-content:end;">
                <label class="checkbox-row"><input type="checkbox" name="is_featured" value="1" <?= ! empty($p['is_featured']) ? 'checked' : '' ?>> Featured on home</label>
                <label class="checkbox-row"><input type="checkbox" name="is_published" value="1" <?= ($p['is_published'] ?? 1) ? 'checked' : '' ?>> Published</label>
            </div>
            <div class="form-field full"><button class="btn btn-primary" type="submit"><?= $project ? 'Update project' : 'Create project' ?></button></div>
        </form>
    </div>

    <div>
        <?php if ($project): ?>
            <div class="panel" style="margin-bottom:1rem;">
                <h2>Add media</h2>
                <form method="POST" class="form-grid">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add_media">
                    <div class="form-field full">
                        <label>Type</label>
                        <select name="type" required>
                            <option value="image">Image (CDN)</option>
                            <option value="video_cdn">Video (CDN)</option>
                            <option value="video_youtube">Video (YouTube)</option>
                        </select>
                    </div>
                    <div class="form-field full"><label>URL *</label><input name="url" required></div>
                    <div class="form-field full"><label>Thumbnail URL</label><input name="thumbnail_url"></div>
                    <div class="form-field"><label>Caption</label><input name="caption"></div>
                    <div class="form-field"><label>Sort order</label><input type="number" name="sort_order" value="0"></div>
                    <div class="form-field full"><button class="btn btn-accent" type="submit">Add media</button></div>
                </form>
            </div>
            <div class="panel">
                <h2>Media list</h2>
                <table class="table">
                    <thead><tr><th>Type</th><th>URL / caption</th><th></th></tr></thead>
                    <tbody>
                    <?php if ($media): ?>
                        <?php foreach ($media as $m): ?>
                            <tr>
                                <td><?= e($m['type']) ?></td>
                                <td><div style="word-break:break-all;"><?= e(excerpt($m['url'], 60)) ?></div><?php if ($m['caption']): ?><small><?= e($m['caption']) ?></small><?php endif; ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Remove media?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete_media">
                                        <input type="hidden" name="media_id" value="<?= (int) $m['id'] ?>">
                                        <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No media yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel"><p>Save the project first, then add CDN images and CDN / YouTube videos.</p></div>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__.'/../includes/layout/admin-footer.php'; ?>
