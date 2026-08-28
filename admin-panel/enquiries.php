<?php

declare(strict_types=1);

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'mark_read') {
        $pdo->prepare('UPDATE enquiries SET is_read=1, updated_at=? WHERE id=?')->execute([now(), (int) ($_POST['id'] ?? 0)]);
        flash('success', 'Marked as read.');
        redirect('panel/enquiries?id='.(int) ($_POST['id'] ?? 0));
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM enquiries WHERE id = ?')->execute([$id]);
        flash('success', 'Enquiry deleted.');
        redirect('panel/enquiries');
    }
}

$viewId = (int) ($_GET['id'] ?? 0);
$view = null;
if ($viewId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM enquiries WHERE id = ?');
    $stmt->execute([$viewId]);
    $view = $stmt->fetch();
    if ($view && ! $view['is_read']) {
        $pdo->prepare('UPDATE enquiries SET is_read=1, updated_at=? WHERE id=?')->execute([now(), $viewId]);
        $view['is_read'] = 1;
    }
}

$enquiries = $pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Enquiries';
$heading = 'Enquiries';
$currentAdmin = 'enquiries';
require __DIR__.'/../includes/layout/admin-header.php';
?>
<div class="split">
    <div class="panel">
        <table class="table">
            <thead><tr><th>Name</th><th>Phone</th><th>Date</th><th>Read</th></tr></thead>
            <tbody>
            <?php foreach ($enquiries as $row): ?>
                <tr style="<?= ($viewId === (int) $row['id']) ? 'background:var(--surface-2);' : '' ?>">
                    <td><a href="<?= e(url('panel/enquiries?id='.$row['id'])) ?>"><?= e($row['name']) ?></a></td>
                    <td><?= e($row['phone']) ?></td>
                    <td><?= e(format_date(substr($row['created_at'] ?? '', 0, 10))) ?></td>
                    <td><?= $row['is_read'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <?php if ($view): ?>
            <h2><?= e($view['name']) ?></h2>
            <p><strong>Phone:</strong> <?= e($view['phone']) ?></p>
            <?php if ($view['email']): ?><p><strong>Email:</strong> <?= e($view['email']) ?></p><?php endif; ?>
            <?php if ($view['city']): ?><p><strong>City:</strong> <?= e($view['city']) ?></p><?php endif; ?>
            <?php if ($view['project_interest']): ?><p><strong>Interest:</strong> <?= e($view['project_interest']) ?></p><?php endif; ?>
            <p><strong>Received:</strong> <?= e($view['created_at']) ?></p>
            <div style="white-space:pre-line;margin:1rem 0;padding:1rem;background:var(--surface-2);border-radius:12px;"><?= e($view['message']) ?></div>
            <form method="POST" onsubmit="return confirm('Delete this enquiry?')">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int) $view['id'] ?>">
                <button class="btn btn-secondary" type="submit">Delete</button>
            </form>
        <?php else: ?>
            <p>Select an enquiry to view details.</p>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__.'/../includes/layout/admin-footer.php'; ?>
