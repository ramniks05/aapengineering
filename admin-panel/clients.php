<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $pdo->prepare('DELETE FROM clients WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        flash('success', 'Client deleted.');
        redirect('panel/clients');
    }

    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        flash('error', 'Name is required.');
        redirect('panel/clients');
    }

    $data = [
        $name,
        trim($_POST['logo_url'] ?? '') ?: null,
        trim($_POST['website_url'] ?? '') ?: null,
        trim($_POST['industry'] ?? '') ?: null,
        (int) ($_POST['sort_order'] ?? 0),
        isset($_POST['is_active']) ? 1 : 0,
        now(),
    ];

    $editId = (int) ($_POST['id'] ?? 0);
    if ($editId > 0) {
        $data[] = $editId;
        $pdo->prepare('UPDATE clients SET name=?, logo_url=?, website_url=?, industry=?, sort_order=?, is_active=?, updated_at=? WHERE id=?')->execute($data);
        flash('success', 'Client updated.');
    } else {
        $pdo->prepare('INSERT INTO clients (name, logo_url, website_url, industry, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([...array_slice($data, 0, 6), now(), now()]);
        flash('success', 'Client added.');
    }
    redirect('panel/clients');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$clients = $pdo->query('SELECT * FROM clients ORDER BY sort_order, name')->fetchAll();

$pageTitle = 'Clients';
$heading = 'Clients';
$currentAdmin = 'clients';
require __DIR__.'/../includes/layout/admin-header.php';

$c = $edit ?: ['name' => '', 'logo_url' => '', 'website_url' => '', 'industry' => '', 'sort_order' => 0, 'is_active' => 1];
?>
<div class="split">
    <div class="panel">
        <h2><?= $edit ? 'Edit client' : 'Add client' ?></h2>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id'] ?>"><?php endif; ?>
            <div class="form-field full"><label>Name *</label><input name="name" value="<?= e($c['name']) ?>" required></div>
            <div class="form-field full"><label>Logo URL</label><input name="logo_url" value="<?= e($c['logo_url'] ?? '') ?>"></div>
            <div class="form-field full"><label>Website URL</label><input name="website_url" value="<?= e($c['website_url'] ?? '') ?>"></div>
            <div class="form-field"><label>Industry</label><input name="industry" value="<?= e($c['industry'] ?? '') ?>"></div>
            <div class="form-field"><label>Sort order</label><input type="number" name="sort_order" value="<?= (int) ($c['sort_order'] ?? 0) ?>"></div>
            <div class="form-field full"><label class="checkbox-row"><input type="checkbox" name="is_active" value="1" <?= ($c['is_active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            <div class="form-field full"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add' ?></button></div>
        </form>
    </div>
    <div class="panel">
        <table class="table">
            <thead><tr><th>Name</th><th>Active</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($clients as $row): ?>
                <tr>
                    <td><?= e($row['name']) ?></td>
                    <td><?= $row['is_active'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="<?= e(url('panel/clients?edit='.$row['id'])) ?>">Edit</a> ·
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
