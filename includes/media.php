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
        'specialist' => '/assets/generated/hq-specialist.php?v=1',
        'studio' => '/assets/generated/specialist-studio.php?v=2',
        'treatment' => '/assets/generated/hq-treatment.php?v=1',
        'products' => '/assets/generated/products.php?v=2',
        'before' => '/assets/generated/ref-case-before.php?v=2',
        'after' => '/assets/generated/ref-case-after-glow.php?v=2',
        'after_tone' => '/assets/generated/ref-case-after-tone.php?v=2',
        'references' => '/assets/generated/ref-all-generated.php?v=2',
    ];
}

function media_demo_maps(): array
{
    $g = media_generated_assets();
    return [
        'services' => [
            'diagnostika-kozhi' => $g['specialist'],
            'glubokoe-ochishchenie' => $g['treatment'],
            'uvlazhnenie-glow' => $g['after'],
            'obnovlenie-kozhi' => $g['products'],
            'lifting-uhod' => $g['after_tone'],
            'personalnyj-kurs' => $g['studio'],
        ],
        'articles' => [
            'kak-ponyat-chto-narushen-barer-kozhi' => $g['products'],
            'minimalnyj-domashnij-uhod' => $g['after'],
            'zachem-konsultaciya-pered-proceduroj' => $g['specialist'],
        ],
        'videos' => [
            'video-demo-1' => $g['studio'],
            'video-demo-2' => $g['treatment'],
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
    if ($side !== 'after') return $generated['before'];

    $slug = (string)($case['slug'] ?? '');
    return $slug === 'rovnyj-ton-i-siyanie' ? $generated['after_tone'] : $generated['after'];
}

function media_specialist_url(): string
{
    $generated = media_generated_assets();
    return $generated['specialist'];
}

function media_reference_board_url(): string
{
    $generated = media_generated_assets();
    return $generated['references'];
}
