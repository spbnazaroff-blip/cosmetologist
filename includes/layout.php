<?php

declare(strict_types=1);

require_once __DIR__ . '/site-settings.php';
require_once __DIR__ . '/media.php';

function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function page_title(array $site, string $title = ''): string
{
    return $title !== '' ? $title . ' — ' . $site['site_name'] : $site['seo']['title'];
}

function site_booking_href(array $site): string
{
    $value = trim((string)($site['booking_url'] ?? ''));
    return $value !== '' ? $value : '/#booking';
}

function site_phone_href(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    return $digits !== '' ? 'tel:+' . $digits : '';
}

function site_telegram_href(string $value): string
{
    $value = trim($value);
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    return 'https://t.me/' . ltrim($value, '@');
}

function site_whatsapp_href(string $value): string
{
    $value = trim($value);
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    $digits = preg_replace('/\D+/', '', $value) ?? '';
    return $digits !== '' ? 'https://wa.me/' . $digits : '';
}

function render_header(array $site, string $active = '', string $title = '', string $description = '', array $seoOverride = []): void
{
    $key = $active !== '' ? $active : 'home';
    $fallbackTitle = $title !== '' ? page_title($site, $title) : (string)$site['seo']['title'];
    $fallbackDescription = $description !== '' ? $description : (string)$site['seo']['description'];
    $seo = seo_page($key, [
        'title'=>$fallbackTitle,
        'description'=>$fallbackDescription,
        'robots'=>(string)($site['seo']['robots'] ?? 'noindex,nofollow'),
    ], $seoOverride);
    $bookingHref = site_booking_href($site);
    ?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#12100f">
<meta name="robots" content="<?= esc((string)$seo['robots']) ?>">
<meta name="description" content="<?= esc((string)$seo['description']) ?>">
<title><?= esc((string)$seo['title']) ?></title>
<?php if(trim((string)$seo['canonical'])!==''): ?><link rel="canonical" href="<?= esc((string)$seo['canonical']) ?>"><?php endif; ?>
<meta property="og:type" content="website">
<meta property="og:title" content="<?= esc((string)$seo['og_title']) ?>">
<meta property="og:description" content="<?= esc((string)$seo['og_description']) ?>">
<?php if(trim((string)$seo['og_image'])!==''): ?><meta property="og:image" content="<?= esc((string)$seo['og_image']) ?>"><?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<link rel="preload" as="image" href="/assets/generated/hq-treatment.php?v=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=4">
<link rel="stylesheet" href="/assets/css/premium.css?v=3">
<link rel="stylesheet" href="/assets/css/generated-media.css?v=4">
<link rel="stylesheet" href="/assets/css/creative.css?v=1">
<link rel="stylesheet" href="/assets/css/mobile-stability.css?v=1">
<link rel="stylesheet" href="/assets/css/visual-stability.css?v=1">
<link rel="stylesheet" href="/assets/css/signature-typography.css?v=1">
</head>
<body>
<div class="grain" aria-hidden="true"></div>
<div class="scroll-progress" aria-hidden="true"><span></span></div>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="/" aria-label="На главную">
      <span class="brand-mark">É</span><span class="brand-copy"><strong><?= esc((string)$site['site_name']) ?></strong><small>skin atelier</small></span>
    </a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" aria-label="Открыть меню"><span></span><span></span></button>
    <nav class="main-nav" id="main-nav">
      <a class="<?= $active === 'services' ? 'is-active' : '' ?>" href="/services.php">Услуги</a>
      <a class="<?= $active === 'price' ? 'is-active' : '' ?>" href="/price.php">Цены</a>
      <a class="<?= $active === 'cases' ? 'is-active' : '' ?>" href="/cases.php">Результаты</a>
      <a class="<?= $active === 'blog' ? 'is-active' : '' ?>" href="/blog.php">Блог</a>
      <a class="<?= $active === 'videos' ? 'is-active' : '' ?>" href="/videos.php">Видео</a>
      <a href="/#about">Специалист</a>
      <a href="/#booking">Контакты</a>
    </nav>
    <a class="button button-outline header-cta" href="<?= esc($bookingHref) ?>">Записаться</a>
  </div>
</header>
<?php
}

function render_page_hero(string $kicker, string $title, string $lead): void
{
    ?>
<section class="page-hero">
  <div class="container">
    <div class="breadcrumbs"><a href="/">Главная</a> / <?= esc(strip_tags($kicker)) ?></div>
    <div class="page-hero-grid reveal">
      <h1><?= $title ?></h1>
      <p class="lead"><?= esc($lead) ?></p>
    </div>
  </div>
</section>
<?php
}

function render_generated_reference_section(): void
{
    ?>
<section class="generated-reference-section" aria-label="Визуальная концепция">
  <div class="container">
    <div class="generated-reference-head reveal">
      <h2>Визуальная история <em>будущей съёмки.</em></h2>
      <p>Все кадры в этом демонстрационном проекте сгенерированы специально под стиль сайта. Они показывают направление для реальной контент-съёмки: портрет, консультация, процедуры, кабинет, уход и предметные детали.</p>
    </div>
    <figure class="generated-reference-board reveal">
      <img src="<?= esc(media_reference_board_url()) ?>" alt="Референсы будущей контент-съёмки косметолога" loading="lazy" decoding="async">
      <figcaption class="generated-reference-badge">generated · shooting reference</figcaption>
    </figure>
  </div>
</section>
<?php
}

function render_footer(array $site): void
{
    $bookingHref = site_booking_href($site);
    $phone = trim((string)($site['phone'] ?? ''));
    $telegram = trim((string)($site['telegram'] ?? ''));
    $whatsapp = trim((string)($site['whatsapp'] ?? ''));
    $address = trim((string)($site['address'] ?? ''));
    ?>
<footer class="site-footer">
  <div class="container footer-top">
    <a class="brand" href="/"><span class="brand-mark">É</span><span class="brand-copy"><strong><?= esc((string)$site['site_name']) ?></strong><small>skin atelier</small></span></a>
    <div><p>Премиальный сайт специалиста эстетической косметологии: услуги, кейсы, экспертные материалы и понятный путь к записи.</p><?php if($phone!==''||$telegram!==''||$whatsapp!==''||$address!==''): ?><div class="footer-contacts"><?php if($phone!=='' && site_phone_href($phone)!==''): ?><a href="<?=esc(site_phone_href($phone))?>"><?=esc($phone)?></a><?php endif;?><?php if($telegram!=='' && site_telegram_href($telegram)!==''): ?><a href="<?=esc(site_telegram_href($telegram))?>" target="_blank" rel="noopener">Telegram</a><?php endif;?><?php if($whatsapp!=='' && site_whatsapp_href($whatsapp)!==''): ?><a href="<?=esc(site_whatsapp_href($whatsapp))?>" target="_blank" rel="noopener">WhatsApp</a><?php endif;?><?php if($address!==''): ?><span><?=esc($address)?></span><?php endif;?></div><?php endif;?></div>
    <nav class="footer-nav"><a href="/services.php">Услуги</a><a href="/price.php">Цены</a><a href="/cases.php">Результаты</a><a href="/blog.php">Блог</a><a href="/videos.php">Видео</a><a href="/#about">Специалист</a><a href="<?=esc($bookingHref)?>">Запись</a></nav>
  </div>
  <div class="container footer-bottom"><span>© <?= date('Y') ?> <?= esc((string)$site['site_name']) ?></span><span>Информация на сайте не является медицинской рекомендацией и не заменяет консультацию специалиста.</span><a class="admin-link" href="/admin/">Управление сайтом</a></div>
</footer>
<div class="mobile-booking"><a class="button button-dark button-full" href="<?=esc($bookingHref)?>">Записаться <span>↗</span></a></div>
<script src="/assets/js/app.js?v=7" defer></script>
</body></html>
<?php
}
