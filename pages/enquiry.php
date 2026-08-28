<?php

declare(strict_types=1);

$currentPath = 'enquiry';
$pageTitle = 'Enquiry | AAP Engineerings';
$errors = [];
$old = $_POST ?: ['project_interest' => $_GET['interest'] ?? ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $interest = trim($_POST['project_interest'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($phone === '') {
        $errors[] = 'Phone is required.';
    }
    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    if (! $errors) {
        $stmt = $pdo->prepare('INSERT INTO enquiries (name, phone, email, city, project_interest, message, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([$name, $phone, $email ?: null, $city ?: null, $interest ?: null, $message, now(), now()]);
        flash('success', 'Thank you — your enquiry was submitted. Our team will contact you soon.');
        redirect('enquiry');
    }
}

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Project enquiry</h1>
    <p class="lede">Tell us about your electrical project. Our team will get back to you.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container" style="max-width:760px;">
        <div class="panel">
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:1.1rem;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>
            <form method="POST" class="form-grid">
                <?= csrf_field() ?>
                <div class="form-field"><label for="name">Name *</label><input id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required></div>
                <div class="form-field"><label for="phone">Phone *</label><input id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>" required></div>
                <div class="form-field"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>"></div>
                <div class="form-field"><label for="city">City</label><input id="city" name="city" value="<?= e($old['city'] ?? '') ?>"></div>
                <div class="form-field full"><label for="project_interest">Project interest</label><input id="project_interest" name="project_interest" value="<?= e($old['project_interest'] ?? '') ?>"></div>
                <div class="form-field full"><label for="message">Message *</label><textarea id="message" name="message" rows="6" required><?= e($old['message'] ?? '') ?></textarea></div>
                <div class="form-field full"><button class="btn btn-primary" type="submit">Submit enquiry</button></div>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
