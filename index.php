<?php

declare(strict_types=1);

$site = require __DIR__ . '/config.php';

$services = [
    ['title' => 'Консультация и разбор ухода', 'text' => 'Оценка текущего состояния кожи, привычек и домашнего ухода с понятными рекомендациями.'],
    ['title' => 'Уходовые процедуры', 'text' => 'Мягкие протоколы для увлажнения, восстановления барьера и поддержания свежего вида кожи.'],
    ['title' => 'Очищение и обновление', 'text' => 'Процедуры подбираются по показаниям и состоянию кожи, без универсальных схем «для всех».'],
    ['title' => 'Персональная программа', 'text' => 'Последовательный план ухода и процедур с корректировкой по результатам и реакции кожи.'],
];

$steps = [
    ['num' => '01', 'title' => 'Знакомство', 'text' => 'Обсуждаем запрос, особенности кожи и желаемый результат.'],
    ['num' => '02', 'title' => 'Диагностика', 'text' => 'Определяем, что действительно нужно коже сейчас, а что можно не делать.'],
    ['num' => '03', 'title' => 'План', 'text' => 'Формируем последовательность процедур и домашнего ухода.'],
    ['num' => '04', 'title' => 'Сопровождение', 'text' => 'Оцениваем динамику и при необходимости корректируем программу.'],
];

$faqs = [
    ['q' => 'Нужно ли заранее выбирать процедуру?', 'a' => 'Нет. Если вы не уверены, лучше начать с консультации — конкретная процедура определяется после оценки состояния кожи и вашего запроса.'],
    ['q' => 'Можно ли составить только домашний уход?', 'a' => 'Да. Сайт предусматривает консультационный формат без обязательного курса процедур.'],
    ['q' => 'Как узнать стоимость?', 'a' => 'После заполнения реальных данных здесь появится актуальный прайс или ссылка на систему онлайн-записи.'],
    ['q' => 'Можно ли записаться онлайн?', 'a' => 'Да. Для этого предусмотрена отдельная кнопка записи — подключим её после получения ссылки специалиста.'],
];

$bookingUrl = trim((string)($site['booking_url'] ?? ''));
$phone = trim((string)($site['phone'] ?? ''));
$telegram = trim((string)($site['telegram'] ?? ''));
$address = trim((string)($site['address'] ?? ''));
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f0ea">
    <meta name="robots" content="<?= htmlspecialchars($site['seo']['robots'], ENT_QUOTES) ?>">
    <meta name="description" content="<?= htmlspecialchars($site['seo']['description'], ENT_QUOTES) ?>">
    <title><?= htmlspecialchars($site['seo']['title'], ENT_QUOTES) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=1">
</head>
<body>
<header class="site-header" id="top">
    <div class="container header-inner">
        <a class="brand" href="#top" aria-label="На главную">
            <span class="brand-mark">C</span>
            <span class="brand-copy">
                <strong><?= htmlspecialchars($site['site_name']) ?></strong>
                <small><?= htmlspecialchars($site['eyebrow']) ?></small>
            </span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Меню</button>
        <nav class="main-nav" id="main-nav" aria-label="Основная навигация">
            <a href="#services">Услуги</a>
            <a href="#approach">Подход</a>
            <a href="#about">О специалисте</a>
            <a href="#faq">Вопросы</a>
            <a href="#contact">Контакты</a>
        </nav>
        <a class="button button-small button-dark" href="#contact">Записаться</a>
    </div>
</header>

<main>
    <section class="hero section">
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <div class="eyebrow"><?= htmlspecialchars($site['eyebrow']) ?></div>
                <h1><?= htmlspecialchars($site['headline']) ?></h1>
                <p class="hero-lead"><?= htmlspecialchars($site['description']) ?></p>
                <div class="hero-actions">
                    <a class="button button-dark" href="#contact">Записаться на консультацию</a>
                    <a class="text-link" href="#services">Посмотреть услуги <span>↘</span></a>
                </div>
                <div class="hero-meta">
                    <div><strong>Персонально</strong><span>без шаблонных протоколов</span></div>
                    <div><strong>Понятно</strong><span>объясняем каждый этап</span></div>
                    <div><strong>Бережно</strong><span>с уважением к коже</span></div>
                </div>
            </div>
            <div class="hero-visual reveal" aria-label="Декоративная композиция">
                <div class="hero-card hero-card-main">
                    <div class="hero-orbit"></div>
                    <div class="portrait-placeholder">
                        <span>Фото специалиста</span>
                    </div>
                    <div class="floating-note">skin<br>care</div>
                </div>
                <div class="hero-card hero-card-small">
                    <span class="mini-label">Подход</span>
                    <p>Не «больше процедур», а точнее подобранный уход.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="statement section section-tight reveal">
        <div class="container statement-grid">
            <p class="section-kicker">Философия</p>
            <h2>Красивый результат не должен выглядеть как попытка стать кем-то другим.</h2>
        </div>
    </section>

    <section class="services section" id="services">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="section-kicker">Услуги</p>
                    <h2>От консультации до системного ухода</h2>
                </div>
                <p>Наполнение легко заменить на реальные процедуры и цены конкретного специалиста.</p>
            </div>
            <div class="services-grid">
                <?php foreach ($services as $i => $service): ?>
                    <article class="service-card reveal">
                        <span class="service-index">0<?= $i + 1 ?></span>
                        <h3><?= htmlspecialchars($service['title']) ?></h3>
                        <p><?= htmlspecialchars($service['text']) ?></p>
                        <a href="#contact">Уточнить детали <span>↗</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="approach section" id="approach">
        <div class="container approach-grid">
            <div class="approach-intro reveal">
                <p class="section-kicker">Как проходит работа</p>
                <h2>Сначала понять кожу. Потом — выбирать процедуру.</h2>
                <p>Такой сценарий снимает давление с клиента и делает сайт не каталогом процедур, а понятным маршрутом к результату.</p>
            </div>
            <div class="steps">
                <?php foreach ($steps as $step): ?>
                    <article class="step reveal">
                        <span><?= htmlspecialchars($step['num']) ?></span>
                        <div>
                            <h3><?= htmlspecialchars($step['title']) ?></h3>
                            <p><?= htmlspecialchars($step['text']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="about section" id="about">
        <div class="container about-grid">
            <div class="about-visual reveal">
                <div class="about-photo"><span>Портрет / рабочий кадр</span></div>
                <div class="about-badge">Эстетика<br>без лишнего</div>
            </div>
            <div class="about-copy reveal">
                <p class="section-kicker">О специалисте</p>
                <h2><?= htmlspecialchars($site['specialist']) ?></h2>
                <p class="about-lead">Здесь будет короткое сильное представление специалиста: образование, опыт, специализация и главное — подход к клиенту.</p>
                <div class="about-points">
                    <div><span>01</span><p>Только подтверждённые факты и реальные квалификации.</p></div>
                    <div><span>02</span><p>Без медицинских обещаний и искусственно завышенных ожиданий.</p></div>
                    <div><span>03</span><p>Акцент на доверии, визуальной культуре и понятном сервисе.</p></div>
                </div>
            </div>
        </div>
    </section>

    <section class="accent-section section reveal">
        <div class="container accent-inner">
            <p class="section-kicker">Важно</p>
            <h2>Процедуры должны поддерживать индивидуальность, а не стирать её.</h2>
            <p>Эта мысль задаёт тон всему сайту — спокойный, профессиональный и визуально премиальный.</p>
        </div>
    </section>

    <section class="faq section" id="faq">
        <div class="container faq-grid">
            <div class="faq-intro reveal">
                <p class="section-kicker">FAQ</p>
                <h2>Частые вопросы</h2>
                <p>Блок помогает снять основные сомнения ещё до первого сообщения специалисту.</p>
            </div>
            <div class="accordion">
                <?php foreach ($faqs as $i => $faq): ?>
                    <details class="faq-item reveal" <?= $i === 0 ? 'open' : '' ?>>
                        <summary><?= htmlspecialchars($faq['q']) ?><span>+</span></summary>
                        <p><?= htmlspecialchars($faq['a']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="contact section" id="contact">
        <div class="container contact-card reveal">
            <div class="contact-copy">
                <p class="section-kicker">Запись</p>
                <h2>Начнём с вашего запроса</h2>
                <p>Контакты и онлайн-запись подключим после получения данных специалиста. Сейчас форма намеренно не отправляет персональные данные никуда.</p>
            </div>
            <div class="contact-actions">
                <?php if ($bookingUrl !== ''): ?>
                    <a class="button button-light" href="<?= htmlspecialchars($bookingUrl, ENT_QUOTES) ?>" target="_blank" rel="noopener">Онлайн-запись</a>
                <?php endif; ?>
                <?php if ($phone !== ''): ?>
                    <a class="contact-line" href="tel:<?= htmlspecialchars(preg_replace('/[^+0-9]/', '', $phone), ENT_QUOTES) ?>"><span>Телефон</span><strong><?= htmlspecialchars($phone) ?></strong></a>
                <?php endif; ?>
                <?php if ($telegram !== ''): ?>
                    <a class="contact-line" href="<?= htmlspecialchars($telegram, ENT_QUOTES) ?>" target="_blank" rel="noopener"><span>Telegram</span><strong>Написать</strong></a>
                <?php endif; ?>
                <?php if ($address !== ''): ?>
                    <div class="contact-line"><span>Адрес</span><strong><?= htmlspecialchars($address) ?></strong></div>
                <?php endif; ?>
                <?php if ($bookingUrl === '' && $phone === '' && $telegram === '' && $address === ''): ?>
                    <div class="contact-empty">Контактные данные пока не заполнены</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <strong><?= htmlspecialchars($site['site_name']) ?></strong>
            <span><?= htmlspecialchars($site['eyebrow']) ?></span>
        </div>
        <p>© <?= date('Y') ?>. Информация на сайте не заменяет консультацию специалиста.</p>
        <a href="#top">Наверх ↑</a>
    </div>
</footer>

<script src="assets/js/app.js?v=1" defer></script>
</body>
</html>
