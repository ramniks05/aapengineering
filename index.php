<?php

/**
 * Hostinger fallback when domain root is project root (not /public).
 * Prefer setting document root to /public in hPanel when possible.
 */

require __DIR__.'/public/index.php';
