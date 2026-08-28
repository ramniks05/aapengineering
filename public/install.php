<?php

/**
 * One-time Hostinger setup (no SSH needed).
 * Open: https://aapengineerings.com/install.php?key=AapSetup2026
 * DELETE this file immediately after success.
 */

declare(strict_types=1);

$secret = 'AapSetup2026';

if (($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    exit('Forbidden. Add ?key=AapSetup2026 to the URL.');
}

header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);

echo "AAP Engineerings — one-time setup\n";
echo str_repeat('=', 42) . "\n\n";

if (! is_file($root . '/vendor/autoload.php')) {
    exit("ERROR: vendor folder missing.\nRun composer install on server or upload vendor folder.\n");
}

if (! is_file($root . '/.env')) {
    exit("ERROR: .env file missing in project root.\n");
}

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

$commands = [
    'migrate --force' => 'Creating database tables',
    'db:seed --force' => 'Adding demo data + admin user',
    'config:clear' => 'Clearing config cache',
    'cache:clear' => 'Clearing app cache',
    'view:clear' => 'Clearing view cache',
];

foreach ($commands as $command => $label) {
    echo ">> {$label}\n";
    try {
        Artisan::call($command);
        $output = trim(Artisan::output());
        echo $output !== '' ? $output . "\n" : "Done.\n";
    } catch (Throwable $e) {
        echo "FAILED: " . $e->getMessage() . "\n\n";
        echo "If database failed, check .env DB settings in File Manager.\n";
        echo "Or import database/hostinger_database.sql via phpMyAdmin.\n";
        exit(1);
    }
    echo "\n";
}

echo "SUCCESS!\n\n";
echo "Website: https://aapengineerings.com\n";
echo "Admin:   https://aapengineerings.com/manage/login\n";
echo "Email:   admin@aapengineerings.com\n";
echo "Pass:    Admin@123\n\n";
echo "IMPORTANT: Delete public/install.php and public/hostinger-check.php now.\n";
