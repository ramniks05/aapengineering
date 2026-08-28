<?php

/**
 * One-time admin password reset (wrong hash in earlier database.sql).
 * Open: https://aapengineerings.com/reset-admin.php?key=AapSetup2026
 * DELETE this file after success.
 */

declare(strict_types=1);

require __DIR__.'/includes/load-config.php';

$config = load_config();
$key = $config['install_key'] ?? 'AapSetup2026';

if (($_GET['key'] ?? '') !== $key) {
    http_response_code(403);
    exit('Forbidden. Add ?key='.$key.' to the URL.');
}

header('Content-Type: text/plain; charset=utf-8');

require __DIR__.'/includes/db.php';

try {
    $pdo = db_connect($config['db']);
} catch (Throwable $e) {
    exit('Database connection failed: '.$e->getMessage());
}

$hash = password_hash('Admin@123', PASSWORD_BCRYPT);
$email = 'admin@aapengineerings.com';

$stmt = $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?');
$stmt->execute([$hash, $email]);

if ($stmt->rowCount() === 0) {
    $pdo->prepare('INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?,?,?,NOW(),NOW())')
        ->execute(['AAP Admin', $email, $hash]);
    echo "Admin user created.\n";
} else {
    echo "Admin password reset.\n";
}

echo "\nLogin: ".$config['app_url']."/manage/login\n";
echo "Email: admin@aapengineerings.com\n";
echo "Pass:  Admin@123\n\n";
echo "DELETE reset-admin.php from the server now.\n";
