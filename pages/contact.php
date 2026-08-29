<?php

declare(strict_types=1);

$currentPath = 'contact';
$pageTitle = 'Contact Us | '.$config['app_name'];
$company = $config['company'];
$waLink = whatsapp_link($company);
$errors = [];
$old = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'contact') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
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
        $stmt = $pdo->prepare('INSERT INTO enquiries (name, phone, email, message, created_at, updated_at) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$name, $phone, $email ?: null, $message, now(), now()]);
        flash('success', 'Thank you — we received your message and will respond soon.');
        redirect('contact');
    }
}

require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Contact us</h1>
    <p class="lede">Reach our project team by phone, WhatsApp or the form below.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container contact-grid">
        <div class="panel contact-info">
            <h2>Get in touch</h2>
            <p style="margin-top:.9rem;">Phone</p>
            <?php foreach (company_phones($company) as $phone): ?>
                <p><a href="<?= e(phone_href($phone)) ?>"><?= e($phone) ?></a></p>
            <?php endforeach; ?>
            <p>WhatsApp</p>
            <p><a href="<?= e($waLink) ?>" target="_blank" rel="noopener"><?= e($company['phones'][0] ?? $company['phone']) ?></a></p>
            <p>Email</p>
            <p>
                <?php foreach (company_emails($company) as $i => $email): ?>
                    <?php if ($i > 0): ?><br><?php endif; ?>
                    <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                <?php endforeach; ?>
            </p>
            <p>Office address</p>
            <p><?= e($company['address']) ?></p>
            <p>Working hours</p>
            <p><?= e($company['hours'] ?? 'Mon – Sat, 9:00 AM – 6:00 PM') ?></p>
            <div style="display:grid;gap:.7rem;margin-top:1.2rem;">
                <a class="btn btn-primary" href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp us</a>
                <a class="btn btn-secondary" href="<?= e($company['map_link']) ?>" target="_blank" rel="noopener">Open in Google Maps</a>
                <a class="btn btn-secondary" href="<?= e(url('enquiry')) ?>">Project enquiry form</a>
            </div>
        </div>

        <div class="panel">
            <h2>Send a message</h2>
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success" style="margin-top:1rem;"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="alert alert-error" style="margin-top:1rem;">
                    <ul style="margin:0;padding-left:1.1rem;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>
            <form method="POST" class="form-grid" style="margin-top:1rem;">
                <?= csrf_field() ?>
                <input type="hidden" name="form" value="contact">
                <div class="form-field"><label for="name">Name *</label><input id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required></div>
                <div class="form-field"><label for="phone">Phone *</label><input id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>" required></div>
                <div class="form-field full"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>"></div>
                <div class="form-field full"><label for="message">Message *</label><textarea id="message" name="message" rows="5" required><?= e($old['message'] ?? '') ?></textarea></div>
                <div class="form-field full"><button class="btn btn-primary" type="submit">Send message</button></div>
            </form>
        </div>
    </div>

    <div class="container" style="margin-top:2rem;">
        <div class="panel" style="padding:0;overflow:hidden;">
            <iframe src="<?= e($company['map_embed']) ?>" width="100%" height="420" style="border:0;display:block;" loading="lazy" title="Office location"></iframe>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
