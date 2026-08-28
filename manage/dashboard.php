<?php

declare(strict_types=1);

require_admin();

$counts = [
    'projects' => (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'clients' => (int) $pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
    'gallery' => (int) $pdo->query('SELECT COUNT(*) FROM gallery_items')->fetchColumn(),
    'updates' => (int) $pdo->query('SELECT COUNT(*) FROM updates')->fetchColumn(),
    'enquiries' => (int) $pdo->query('SELECT COUNT(*) FROM enquiries WHERE is_read=0')->fetchColumn(),
];

$recentEnquiries = $pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 5')->fetchAll();

$pageTitle = 'Dashboard';
$heading = 'Dashboard';
$currentAdmin = 'dashboard';
require __DIR__.'/../includes/layout/admin-header.php';
?>
<div class="grid-3">
    <div class="panel"><strong><?= $counts['projects'] ?></strong><p>Projects</p></div>
    <div class="panel"><strong><?= $counts['clients'] ?></strong><p>Clients</p></div>
    <div class="panel"><strong><?= $counts['gallery'] ?></strong><p>Gallery items</p></div>
    <div class="panel"><strong><?= $counts['updates'] ?></strong><p>Updates</p></div>
    <div class="panel"><strong><?= $counts['enquiries'] ?></strong><p>Unread enquiries</p></div>
</div>

<div class="panel" style="margin-top:1.5rem;">
    <h2>Recent enquiries</h2>
    <table class="table">
        <thead><tr><th>Name</th><th>Phone</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php if ($recentEnquiries): ?>
            <?php foreach ($recentEnquiries as $row): ?>
                <tr>
                    <td><?= e($row['name']) ?><?= $row['is_read'] ? '' : ' *' ?></td>
                    <td><?= e($row['phone']) ?></td>
                    <td><?= e(format_date(substr($row['created_at'] ?? '', 0, 10))) ?></td>
                    <td><a href="<?= e(url('manage/enquiries?id='.$row['id'])) ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">No enquiries yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__.'/../includes/layout/admin-footer.php'; ?>
