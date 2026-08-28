<?php

/**
 * Upload to public/ and open: https://your-domain.com/hostinger-check.php
 * DELETE this file after fixing the site.
 */
header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);

echo "AAP Engineerings — server check\n";
echo str_repeat('=', 40) . "\n\n";

echo "PHP version: " . PHP_VERSION . "\n";
echo "Project root: {$root}\n\n";

$checks = [
    'artisan' => is_file($root . '/artisan'),
    'vendor/autoload.php' => is_file($root . '/vendor/autoload.php'),
    '.env' => is_file($root . '/.env'),
    'public/css/app.css' => is_file($root . '/public/css/app.css'),
    'storage writable' => is_writable($root . '/storage'),
    'bootstrap/cache writable' => is_writable($root . '/bootstrap/cache'),
];

foreach ($checks as $label => $ok) {
    echo ($ok ? '[OK] ' : '[FAIL] ') . $label . "\n";
}

echo "\n";

if (! is_file($root . '/vendor/autoload.php')) {
    echo "Fix: run composer install in project root.\n\n";
    exit;
}

if (! is_file($root . '/.env')) {
    echo "Fix: copy .env.example to .env and set DB credentials.\n\n";
    exit;
}

require $root . '/vendor/autoload.php';
$app = require_once $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "[OK] Database connection\n";
} catch (Throwable $e) {
    echo "[FAIL] Database connection\n";
    echo 'Error: ' . $e->getMessage() . "\n";
    echo "Fix: check DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env\n\n";
    exit;
}

$tables = ['users', 'sessions', 'projects', 'cities'];
foreach ($tables as $table) {
    try {
        $exists = Illuminate\Support\Facades\Schema::hasTable($table);
        echo ($exists ? '[OK] ' : '[MISSING] ') . "table: {$table}\n";
    } catch (Throwable $e) {
        echo "[FAIL] table check: {$table} — " . $e->getMessage() . "\n";
    }
}

echo "\nIf tables are missing, run:\n";
echo "php artisan migrate --seed\n";
echo "php artisan config:clear\n\n";
echo "Delete hostinger-check.php after site works.\n";
