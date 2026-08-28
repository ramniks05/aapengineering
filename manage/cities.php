<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $pdo->prepare('DELETE FROM cities WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        flash('success', 'City deleted.');
        redirect('manage/cities');
    }

    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        flash('error', 'City name is required.');
        redirect('manage/cities');
    }

    $data = [
        $name,
        trim($_POST['state'] ?? '') ?: null,
        isset($_POST['is_active']) ? 1 : 0,
        now(),
    ];

    $editId = (int) ($_POST['id'] ?? 0);
    if ($editId > 0) {
        $data[] = $editId;
        $pdo->prepare('UPDATE cities SET name=?, state=?, is_active=?, updated_at=? WHERE id=?')->execute($data);
        flash('success', 'City updated.');
    } else {
        $pdo->prepare('INSERT INTO cities (name, state, is_active, created_at, updated_at) VALUES (?,?,?,?,?)')
            ->execute([...array_slice($data, 0, 3), now(), now()]);
        flash('success', 'City added.');
    }
    redirect('manage/cities');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM cities WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$cities = $pdo->query('SELECT * FROM cities ORDER BY name')->fetchAll();

$pageTitle = 'Cities';
$heading = 'Cities';
$currentAdmin = 'cities';
require __DIR__.'/../includes/layout/admin-header.php';

$c = $edit ?: ['name' => '', 'state' => '', 'is_active' => 1];
?>
<div class="split">
    <div class="panel">
        <h2><?= $edit ? 'Edit city' : 'Add city' ?></h2>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id'] ?>"><?php endif; ?>
            <div class="form-field"><label>Name *</label><input name="name" value="<?= e($c['name']) ?>" required></div>
            <div class="form-field"><label>State</label><input name="state" value="<?= e($c['state'] ?? '') ?>"></div>
            <div class="form-field full"><label class="checkbox-row"><input type="checkbox" name="is_active" value="1" <?= ($c['is_active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            <div class="form-field full"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add' ?></button></div>
        </form>
    </div>
    <div class="panel">
        <table class="table">
            <thead><tr><th>City</th><th>State</th><th>Active</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($cities as $row): ?>
                <tr>
                    <td><?= e($row['name']) ?></td>
                    <td><?= e($row['state'] ?? '—') ?></td>
                    <td><?= $row['is_active'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="<?= e(url('manage/cities?edit='.$row['id'])) ?>">Edit</a> ·
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete? Projects will keep but lose city link.')">
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
