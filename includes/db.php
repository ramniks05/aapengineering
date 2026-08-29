<?php

declare(strict_types=1);

function db_connect(array $db): PDO
{
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['name'],
        $db['charset'] ?? 'utf8mb4'
    );

    return new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function db_migrate(PDO $pdo): void
{
    if (! empty($_SESSION['_db_migrated_v1'])) {
        return;
    }

    $alters = [
        'ALTER TABLE clients MODIFY logo_url VARCHAR(500) NULL',
        'ALTER TABLE clients MODIFY website_url VARCHAR(500) NULL',
        'ALTER TABLE projects MODIFY cover_image_url VARCHAR(500) NULL',
        'ALTER TABLE project_media MODIFY url VARCHAR(500) NOT NULL',
        'ALTER TABLE project_media MODIFY thumbnail_url VARCHAR(500) NULL',
        'ALTER TABLE gallery_items MODIFY url VARCHAR(500) NOT NULL',
        'ALTER TABLE gallery_items MODIFY thumbnail_url VARCHAR(500) NULL',
        'ALTER TABLE updates MODIFY cover_image_url VARCHAR(500) NULL',
    ];

    foreach ($alters as $sql) {
        try {
            $pdo->exec($sql);
        } catch (Throwable) {
            // Column may already be updated or table missing during install.
        }
    }

    $_SESSION['_db_migrated_v1'] = true;
}
