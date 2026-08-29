<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function config(array $config, string $key, mixed $default = ''): mixed
{
    $keys = explode('.', $key);
    $value = $config;
    foreach ($keys as $k) {
        if (! is_array($value) || ! array_key_exists($k, $value)) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}

function url(string $path = ''): string
{
    global $config;
    $base = rtrim($config['app_url'] ?? '', '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base.'/' : $base.'/'.$path;
}

function asset(string $path): string
{
    return url('assets/'.ltrim($path, '/'));
}

function redirect(string $path): never
{
    header('Location: '.url($path));
    exit;
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');

    return $text !== '' ? $text : 'item';
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;

        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function is_logged_in(): bool
{
    return ! empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (! is_logged_in()) {
        redirect('panel/login');
    }
}

function youtube_id(string $url): ?string
{
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $url, $m)) {
        return $m[1];
    }
    if (preg_match('~^[A-Za-z0-9_-]{11}$~', $url)) {
        return $url;
    }

    return null;
}

function youtube_embed(string $url): ?string
{
    $id = youtube_id($url);

    return $id ? 'https://www.youtube.com/embed/'.$id : null;
}

function whatsapp_link(array $company): string
{
    $num = preg_replace('/\D+/', '', $company['whatsapp'] ?? '');

    return 'https://wa.me/'.$num.'?text='.rawurlencode($company['whatsapp_message'] ?? '');
}

function status_label(string $status): string
{
    return match ($status) {
        'upcoming' => 'Upcoming',
        'ongoing' => 'Ongoing',
        'completed' => 'Completed',
        default => ucfirst($status),
    };
}

function excerpt(?string $text, int $limit = 120): string
{
    $text = trim(strip_tags((string) $text));
    if (strlen($text) <= $limit) {
        return $text;
    }

    return substr($text, 0, $limit).'…';
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="'.e(csrf_token()).'">';
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (! hash_equals(csrf_token(), (string) $token)) {
        http_response_code(419);
        exit('Invalid form token. Go back and try again.');
    }
}

function media_preview_url(array $item): ?string
{
    $type = $item['type'] ?? 'image';
    if ($type === 'image') {
        return $item['url'] ?? null;
    }
    if ($type === 'video_cdn') {
        return $item['thumbnail_url'] ?? $item['url'] ?? null;
    }
    if ($type === 'video_youtube') {
        $id = youtube_id($item['url'] ?? '');

        return $id ? 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg' : null;
    }

    return null;
}

function media_lightbox(array $item): array
{
    $type = $item['type'] ?? 'image';
    if ($type === 'video_youtube') {
        $embed = youtube_embed($item['url'] ?? '');

        return ['type' => 'youtube', 'src' => $embed ? $embed.'?autoplay=1' : ''];
    }
    if ($type === 'video_cdn') {
        return ['type' => 'video', 'src' => $item['url'] ?? ''];
    }

    return ['type' => 'image', 'src' => $item['url'] ?? ''];
}

function format_date(?string $date): string
{
    if (! $date) {
        return '';
    }
    $ts = strtotime($date);

    return $ts ? date('d M Y', $ts) : '';
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function clip_text(?string $value, int $max = 500): ?string
{
    if ($value === null || $value === '') {
        return null;
    }
    $value = trim($value);

    return strlen($value) > $max ? substr($value, 0, $max) : $value;
}
