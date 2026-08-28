<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}
$_SESSION = [];
session_destroy();
redirect('manage/login');
