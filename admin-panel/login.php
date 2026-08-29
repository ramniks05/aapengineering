<?php

declare(strict_types=1);

if (is_logged_in()) {
    redirect('panel');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        redirect('panel');
    }
    $errors[] = 'Invalid email or password.';
}

$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | AAP Engineerings</title>
    <link rel="stylesheet" href="<?= e(asset('css/app.css?v=5')) ?>">
</head>
<body class="admin-body">
<div class="container" style="max-width:420px;margin:4rem auto;padding:0 1rem;">
    <div class="panel">
        <h1 style="font-family:var(--font-display);margin:0 0 .5rem;">AAP Admin</h1>
        <p style="color:var(--muted);margin:0 0 1.5rem;">Sign in to manage projects and content.</p>
        <?php if ($errors): ?>
            <div class="alert alert-error"><ul style="margin:0;padding-left:1.1rem;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field full"><label>Email</label><input type="email" name="email" required autofocus></div>
            <div class="form-field full"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-field full"><button class="btn btn-primary" type="submit">Login</button></div>
        </form>
        <p style="margin-top:1rem;"><a href="<?= e(url()) ?>">← Back to website</a></p>
    </div>
</div>
</body>
</html>
