<?php
/** @var array $config */
$heading = $heading ?? 'Dashboard';
$currentAdmin = $currentAdmin ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? ($config['app_name'] ?? 'AAP Engineering')) ?> | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/app.css?v=5')) ?>">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-side">
        <div style="padding:0 .85rem 1.2rem;">
            <strong style="font-family:var(--font-display);font-size:1.2rem;">AAP Admin</strong>
        </div>
        <?php
        $nav = [
            'dashboard' => ['panel', 'Dashboard'],
            'projects' => ['panel/projects', 'Projects'],
            'clients' => ['panel/clients', 'Clients'],
            'gallery' => ['panel/gallery', 'Gallery'],
            'updates' => ['panel/updates', 'Updates'],
            'cities' => ['panel/cities', 'Cities'],
            'enquiries' => ['panel/enquiries', 'Enquiries'],
        ];
        foreach ($nav as $key => [$href, $label]):
            $active = ($currentAdmin === $key) ? 'active' : '';
        ?>
            <a href="<?= e(url($href)) ?>" class="<?= $active ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
        <a href="<?= e(url()) ?>" target="_blank">View website</a>
        <form method="POST" action="<?= e(url('panel/logout')) ?>" style="margin-top:1rem;padding:0 .85rem;">
            <?= csrf_field() ?>
            <button class="btn btn-secondary" type="submit" style="width:100%;color:#12261e;">Logout</button>
        </form>
    </aside>
    <div class="admin-main">
        <div class="admin-top">
            <h1 style="margin:0;font-family:var(--font-display);font-size:1.6rem;"><?= e($heading) ?></h1>
            <?= $adminActions ?? '' ?>
        </div>
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.1rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
