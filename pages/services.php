<?php

declare(strict_types=1);

$currentPath = 'services';
$pageTitle = 'Services | '.$config['app_name'];
require __DIR__.'/../includes/layout/header.php';
?>
<div class="container page-hero">
    <h1>Services</h1>
    <p class="lede">Electrical goods and complete project services under one accountable team.</p>
</div>

<section class="section" style="padding-top:0;">
    <div class="container service-list">
        <div class="service-item panel">
            <h3>HT / LT Electrical Installation</h3>
            <p>Distribution, cabling, panels, transformers coordination and commissioning for industrial and commercial sites.</p>
        </div>
        <div class="service-item panel">
            <h3>Industrial & Commercial Fit-outs</h3>
            <p>Power, lighting, earthing and safety systems for plants, offices, warehouses and campuses.</p>
        </div>
        <div class="service-item panel">
            <h3>Project Supply & Execution</h3>
            <p>Electrical goods supply aligned with site execution — one team for material and installation.</p>
        </div>
        <div class="service-item panel">
            <h3>Upgrades & Maintenance Projects</h3>
            <p>Phased upgrades for hospitals, factories and facilities with minimal downtime planning.</p>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="band">
            <div class="split" style="position:relative;z-index:1;align-items:center;">
                <div>
                    <h2 style="font-family:var(--font-display);margin:0 0 .6rem;font-size:2rem;">Need a scoped quotation?</h2>
                    <p style="margin:0;color:rgba(255,255,255,.82);">Tell us the city, timeline and electrical scope — we will respond with next steps.</p>
                </div>
                <div style="display:grid;gap:.7rem;">
                    <a href="<?= e(url('enquiry')) ?>" class="btn btn-primary">Send enquiry</a>
                    <a href="<?= e(url('contact')) ?>" class="btn btn-ghost">Contact details</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/layout/footer.php'; ?>
