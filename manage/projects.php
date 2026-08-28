<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $pdo->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
    flash('success', 'Project deleted.');
    redirect('manage/projects');
}

$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$where = ['1=1'];
$params = [];
if ($q !== '') {
    $where[] = '(p.title LIKE ? OR p.client_name LIKE ?)';
    $like = '%'.$q.'%';
    $params[] = $like;
    $params[] = $like;
}
if (in_array($status, ['upcoming', 'ongoing', 'completed'], true)) {
    $where[] = 'p.status = ?';
    $params[] = $status;
}
$whereSql = implode(' AND ', $where);
$stmt = $pdo->prepare("SELECT p.*, c.name AS city_name FROM projects p LEFT JOIN cities c ON c.id=p.city_id WHERE {$whereSql} ORDER BY p.sort_order, p.created_at DESC");
$stmt->execute($params);
$projects = $stmt->fetchAll();

$pageTitle = 'Projects';
$heading = 'Projects';
$currentAdmin = 'projects';
$adminActions = '<a href="'.e(url('manage/project')).'" class="btn btn-primary">Add project</a>';
require __DIR__.'/../includes/layout/admin-header.php';
?>
<form method="GET" class="filters">
    <div><input type="text" name="q" value="<?= e($q) ?>" placeholder="Search title / client"></div>
    <div>
        <select name="status">
            <option value="">All statuses</option>
            <?php foreach (['upcoming', 'ongoing', 'completed'] as $s): ?>
                <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="flex:0 0 auto;"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>

<div class="panel">
    <table class="table">
        <thead><tr><th>Title</th><th>Status</th><th>City</th><th>Published</th><th></th></tr></thead>
        <tbody>
        <?php if ($projects): ?>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td><?= e($p['title']) ?></td>
                    <td><span class="badge <?= e($p['status']) ?>"><?= e(status_label($p['status'])) ?></span></td>
                    <td><?= e($p['city_name'] ?? '—') ?></td>
                    <td><?= $p['is_published'] ? 'Yes' : 'No' ?></td>
                    <td style="white-space:nowrap;">
                        <a href="<?= e(url('manage/project?id='.$p['id'])) ?>">Edit</a> ·
                        <a href="<?= e(url('project/'.$p['slug'])) ?>" target="_blank">View</a> ·
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                            <button type="submit" style="background:none;border:0;color:#8a1f1f;cursor:pointer;padding:0;font:inherit;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No projects found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__.'/../includes/layout/admin-footer.php'; ?>
