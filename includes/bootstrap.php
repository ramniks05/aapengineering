<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$configFile = dirname(__DIR__) . '/config.php';
if (! is_file($configFile)) {
    http_response_code(503);
    exit('Missing config.php — copy config.example.php to config.php and set your database details.');
}

$config = require $configFile;

require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

$pdo = db_connect($config['db']);
