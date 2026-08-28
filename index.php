<?php

declare(strict_types=1);

require __DIR__.'/includes/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = trim((string) $uri, '/');

$routes = [
    '' => 'pages/home.php',
    'about' => 'pages/about.php',
    'services' => 'pages/services.php',
    'projects' => 'pages/projects.php',
    'clients' => 'pages/clients.php',
    'gallery' => 'pages/gallery.php',
    'updates' => 'pages/updates.php',
    'contact' => 'pages/contact.php',
    'enquiry' => 'pages/enquiry.php',
    'manage/login' => 'manage/login.php',
    'manage/logout' => 'manage/logout.php',
    'manage' => 'manage/dashboard.php',
    'manage/projects' => 'manage/projects.php',
    'manage/clients' => 'manage/clients.php',
    'manage/gallery' => 'manage/gallery.php',
    'manage/updates' => 'manage/updates.php',
    'manage/project' => 'manage/project-form.php',
    'manage/cities' => 'manage/cities.php',
    'manage/enquiries' => 'manage/enquiries.php',
];

if (preg_match('#^project/([a-z0-9-]+)$#', $uri, $m)) {
    $_GET['slug'] = $m[1];
    require __DIR__.'/pages/project.php';
    exit;
}

if (preg_match('#^update/([a-z0-9-]+)$#', $uri, $m)) {
    $_GET['slug'] = $m[1];
    require __DIR__.'/pages/update.php';
    exit;
}

if (isset($routes[$uri])) {
    require __DIR__.'/'.$routes[$uri];
    exit;
}

http_response_code(404);
$pageTitle = 'Page not found';
require __DIR__.'/includes/layout/header.php';
echo '<div class="container page-hero"><h1>404</h1><p class="lede">Page not found.</p></div>';
require __DIR__.'/includes/layout/footer.php';
