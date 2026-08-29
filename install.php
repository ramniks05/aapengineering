<?php

/**
 * One-time database setup for plain PHP site (no Composer).
 * Open: https://aapengineerings.com/install.php?key=AapSetup2026
 * DELETE this file immediately after success.
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

echo "AAP Engineering — plain PHP setup\n";
echo str_repeat('=', 42)."\n\n";

$sqlFile = __DIR__.'/database.sql';
if (! is_file($sqlFile)) {
    exit("ERROR: database.sql not found.\n");
}

try {
    $db = $config['db'];
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $db['host'], $db['name'], $db['charset'] ?? 'utf8mb4');
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo ">> Connected to database\n\n";
} catch (Throwable $e) {
    exit('ERROR: Database connection failed: '.$e->getMessage()."\nCheck config.php DB settings.\n");
}

$sql = file_get_contents($sqlFile);
$statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql) ?: []));

$ok = 0;
foreach ($statements as $statement) {
    if ($statement === '' || str_starts_with($statement, '--')) {
        continue;
    }
    try {
        $pdo->exec($statement);
        $ok++;
    } catch (Throwable $e) {
        echo "FAILED on statement:\n".substr($statement, 0, 120)."...\n";
        echo $e->getMessage()."\n\n";
        exit(1);
    }
}

echo ">> Executed {$ok} SQL statements\n\n";
echo "SUCCESS!\n\n";
echo "Website: ".$config['app_url']."\n";
echo "Admin:   ".$config['app_url']."/panel/login\n";
echo "Email:   admin@aapengineerings.com\n";
echo "Pass:    Admin@123\n\n";
echo "IMPORTANT: Delete install.php from the server now.\n";
