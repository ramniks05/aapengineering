<?php

declare(strict_types=1);

$currentPath = 'projects';
$pageTitle = 'Projects | '.$config['app_name'];

$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$city = $_GET['city'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

$where = ['p.is_published = 1'];
$params = [];

if ($q !== '') {
    $where[] = '(p.title LIKE ? OR p.client_name LIKE ? OR p.short_description LIKE ?)';
    $like = '%'.$q.'%';
    $params = array_merge($params, [$like, $like, $like]);
}
if (in_array($status, ['upcoming', 'ongoing', 'completed'], true)) {
    $where[] = 'p.status = ?';
    $params[] = $status;
}
if ($city !== '' && ctype_digit($city)) {
    $where[] = 'p.city_id = ?';
    $params[] = (int) $city;
}

$whereSql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM projects p WHERE {$whereSql}");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS city_name
    FROM projects p
    LEFT JOIN cities c ON c.id = p.city_id
    WHERE {$whereSql}
    ORDER BY p.sort_order, p.created_at DESC
    LIMIT {$perPage} OFFSET {$offset}
");
$stmt->execute($params);
$projects = $stmt->fetchAll();

$cities = $pdo->query('SELECT * FROM cities WHERE is_active=1 ORDER BY name')->fetchAll();

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Projects</h1>
    <p class="lede">Filter by status, city or keyword to explore our electrical project portfolio.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <form method="GET" action="<?= e(url('projects')) ?>" class="filters">
            <div><input type="text" name="q" value="<?= e($q) ?>" placeholder="Search projects"></div>
            <div>
                <select name="status">
                    <option value="">All statuses</option>
                    <?php foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $val => $label): ?>
                        <option value="<?= e($val) ?>" <?= $status === $val ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <select name="city">
                    <option value="">All cities</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= (int) $c['id'] ?>" <?= (string) $city === (string) $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['name'].($c['state'] ? ', '.$c['state'] : '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:0 0 auto;display:flex;gap:.5rem;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-secondary" href="<?= e(url('projects')) ?>">Reset</a>
            </div>
        </form>

        <div class="grid-3">
            <?php if ($projects): ?>
                <?php foreach ($projects as $project): ?>
                    <?php require __DIR__.'/../includes/partials/project-card.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="panel" style="grid-column:1/-1;"><p>No projects match your filters.</p></div>
            <?php endif; ?>
        </div>

        <?php if ($pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= e(url('projects?'.http_build_query(array_filter(['q' => $q, 'status' => $status, 'city' => $city, 'page' => $page - 1])))) ?>">← Previous</a>
            <?php endif; ?>
            <span>Page <?= $page ?> of <?= $pages ?></span>
            <?php if ($page < $pages): ?>
                <a href="<?= e(url('projects?'.http_build_query(array_filter(['q' => $q, 'status' => $status, 'city' => $city, 'page' => $page + 1])))) ?>">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
