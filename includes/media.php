<?php

declare(strict_types=1);

function media_safe_url(string $value): string
{
    $value = trim($value);
    if ($value === '') return '';
    if (str_starts_with($value, '/')) return $value;
    $scheme = strtolower((string)(parse_url($value, PHP_URL_SCHEME) ?? ''));
    return in_array($scheme, ['http','https'], true) ? $value : '';
}

function media_generated_assets(): array
{
    return [
        'specialist' => '/assets/generated/specialist-studio.php',
        'treatment' => '/assets/generated/treatment.php',
        'products' => '/assets/generated/products.php',
        'video' => '/assets/generated/video.php',
        'before' => '/assets/generated/case-before.php',
        'after' => '/assets/generated/case-after.php',
        'references' => '/assets/generated/references.php',
    ];
}

function media_demo_maps(): array
{
    $g = media_generated_assets();
    return [
        'services' => [
            'diagnostika-kozhi' => $g['specialist'],
            'glubokoe-ochishchenie' => $g['treatment'],
            'uvlazhnenie-glow' => $g['treatment'],
            'obnovlenie-kozhi' => $g['products'],
            'lifting-uhod' => $g['treatment'],
            'personalnyj-kurs' => $g['specialist'],
        ],
        'articles' => [
            'kak-ponyat-chto-narushen-barer-kozhi' => $g['products'],
            'minimalnyj-domashnij-uhod' => $g['products'],
            'zachem-konsultaciya-pered-proceduroj' => $g['specialist'],
        ],
        'videos' => [
            'video-demo-1' => $g['video'],
            'video-demo-2' => $g['video'],
        ],
    ];
}

function media_cover(array $item, string $type): string
{
    foreach (['cover_image','image','preview_image'] as $field) {
        $candidate = media_safe_url((string)($item[$field] ?? ''));
        if ($candidate !== '') return $candidate;
    }
    $key = (string)($item['slug'] ?? $item['id'] ?? '');
    $maps = media_demo_maps();
    $generated = media_generated_assets();
    return (string)($maps[$type][$key] ?? $generated['specialist']);
}

function media_alt(array $item, string $fallback = ''): string
{
    $alt = trim((string)($item['image_alt'] ?? ''));
    return $alt !== '' ? $alt : ($fallback !== '' ? $fallback : (string)($item['title'] ?? ''));
}

function media_case_image(array $case, string $side): string
{
    $field = $side === 'after' ? 'after_image' : 'before_image';
    $candidate = media_safe_url((string)($case[$field] ?? ''));
    if ($candidate !== '') return $candidate;
    $generated = media_generated_assets();
    return $side === 'after' ? $generated['after'] : $generated['before'];
}

function media_reference_board_url(): string
{
    $generated = media_generated_assets();
    return $generated['references'];
}
