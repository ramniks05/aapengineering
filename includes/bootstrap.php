<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__.'/load-config.php';
$config = load_config();

require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

$pdo = db_connect($config['db']);
