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

function media_demo_maps(): array
{
    return [
        'services' => [
            'diagnostika-kozhi' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=1400&q=86',
            'glubokoe-ochishchenie' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&q=86',
            'uvlazhnenie-glow' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1400&q=86',
            'obnovlenie-kozhi' => 'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?auto=format&fit=crop&w=1400&q=86',
            'lifting-uhod' => 'https://images.unsplash.com/photo-1512316609839-ce289d3eba0a?auto=format&fit=crop&w=1400&q=86',
            'personalnyj-kurs' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=1400&q=86',
        ],
        'articles' => [
            'kak-ponyat-chto-narushen-barer-kozhi' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=1600&q=86',
            'minimalnyj-domashnij-uhod' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=1600&q=86',
            'zachem-konsultaciya-pered-proceduroj' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1600&q=86',
        ],
        'videos' => [
            'video-demo-1' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1600&q=86',
            'video-demo-2' => 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=1600&q=86',
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
    return (string)($maps[$type][$key] ?? 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=1600&q=86');
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

    $slug = (string)($case['slug'] ?? '');
    $sets = [
        'vosstanovlenie-komforta' => [
            'before' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=900&q=84',
            'after' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=900&q=84',
        ],
        'rovnyj-ton-i-siyanie' => [
            'before' => 'https://images.unsplash.com/photo-1512316609839-ce289d3eba0a?auto=format&fit=crop&w=900&q=84',
            'after' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=900&q=84',
        ],
        'rabota-s-teksturoj' => [
            'before' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=84',
            'after' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=900&q=84',
        ],
    ];
    return (string)($sets[$slug][$side] ?? ($side === 'after'
        ? 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=900&q=84'
        : 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=900&q=84'));
}
