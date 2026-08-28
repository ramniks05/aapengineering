<?php

declare(strict_types=1);

/** @var array $config */
$company = $config['company'];
$waLink = whatsapp_link($company);
$current = $currentPath ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'AAP Engineerings') ?></title>
    <meta name="description" content="<?= e($pageMeta ?? 'AAP Engineerings — complete electrical projects and services.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/app.css?v=4')) ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a href="<?= e(url()) ?>" class="brand">
            <div class="brand-mark">AAP</div>
            <div>
                <strong>AAP Engineerings</strong>
                <span>Electrical Projects & Services</span>
            </div>
        </a>
        <button class="nav-toggle" type="button" aria-label="Menu" data-nav-toggle>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        <nav class="nav-links" data-nav>
            <?php
            $nav = [
                '' => 'Home', 'about' => 'About', 'services' => 'Services', 'projects' => 'Projects',
                'clients' => 'Clients', 'gallery' => 'Gallery', 'updates' => 'Updates', 'contact' => 'Contact',
            ];
            foreach ($nav as $path => $label):
                $href = $path === '' ? url() : url($path);
                $active = ($current === $path) ? 'active' : '';
            ?>
                <a href="<?= e($href) ?>" class="<?= $active ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
            <a href="<?= e(url('enquiry')) ?>" class="btn btn-primary">Enquiry</a>
        </nav>
    </div>
</header>
<main>
