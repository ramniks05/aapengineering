<?php

declare(strict_types=1);

/**
 * Load site config: config.php (local override) or config.production.php (Hostinger deploy).
 */
function load_config(): array
{
    $root = dirname(__DIR__);

    foreach (['config.php', 'config.production.php'] as $file) {
        $path = $root.'/'.$file;
        if (is_file($path)) {
            return require $path;
        }
    }

    http_response_code(503);
    exit(
        'Missing config — create config.php from config.example.php, '.
        'or ensure config.production.php is deployed from Git.'
    );
}
