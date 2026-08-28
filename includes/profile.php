<?php

declare(strict_types=1);

function profile_storage_root(): string
{
    if (defined('CONTENT_STORAGE')) return CONTENT_STORAGE;
    $override = trim((string)getenv('COSMETOLOGIST_STORAGE'));
    $siteRoot = dirname(__DIR__);
    $instance = preg_replace('/[^a-z0-9.-]+/i', '-', basename($siteRoot)) ?: 'cosmetologist';
    return $override !== '' ? $override : dirname(__DIR__, 3) . '/cosmetologist-storage/' . $instance;
}

function profile_storage_file(): string
{
    return profile_storage_root() . '/profile.json';
}

function profile_allowed_fields(): array
{
    return ['site_name','eyebrow','headline','description','specialist','city','phone','telegram','whatsapp','address','booking_url'];
}

function site_profile_load(array $defaults): array
{
    $file = profile_storage_file();
    if (!is_file($file)) return $defaults;
    $raw = file_get_contents($file);
    if ($raw === false) return $defaults;
    $saved = json_decode($raw, true);
    if (!is_array($saved)) return $defaults;
    foreach (profile_allowed_fields() as $field) {
        if (array_key_exists($field, $saved)) $defaults[$field] = (string)$saved[$field];
    }
    return $defaults;
}

function site_profile_save(array $data): bool
{
    $clean = [];
    foreach (profile_allowed_fields() as $field) $clean[$field] = trim((string)($data[$field] ?? ''));
    $dir = profile_storage_root();
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) return false;
    $json = json_encode($clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return $json !== false && file_put_contents(profile_storage_file(), $json, LOCK_EX) !== false;
}
