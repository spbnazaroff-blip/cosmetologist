<?php

declare(strict_types=1);

$site = require __DIR__ . '/config.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$services = [
    ['num' => '01', 'title' => 'Диагностика кожи', 'price' => 'от 2 500 ₽', 'text' => 'Разбираем состояние кожи, текущий домашний уход и цели. Формируем понятную персональную стратегию.', 'tag' => 'старт'],
    ['num' => '02', 'title' => 'Глубокое очищение', 'price' => 'от 4 900 ₽', 'text' => 'Деликатное очищение и обновление с подбором протокола по состоянию кожи и её чувствительности.', 'tag' => 'clean'],
    ['num' => '03', 'title' => 'Увлажнение & glow', 'price' => 'от 5 500 ₽', 'text' => 'Протоколы на восстановление барьера, комфорт, ровную текстуру и естественное сияние.', 'tag' => 'glow'],
    ['num' => '04', 'title' => 'Обновление кожи', 'price' => 'от 5 900 ₽', 'text' => 'Мягкие программы обновления кожи с контролем интенсивности и обязательными рекомендациями по восстановлению.', 'tag' => 'renew'],
    ['num' => '05', 'title' => 'Лифтинг-уход', 'price' => 'от 6 500 ₽', 'text' => 'Комплексный эстетический уход с акцентом на тонус, качество кожи и свежий, отдохнувший вид.', 'tag' => 'lift'],
    ['num' => '06', 'title' => 'Персональный курс', 'price' => 'индивидуально', 'text' => 'Последовательная программа процедур и домашнего ухода с отслеживанием динамики и корректировкой.', 'tag' => 'course'],
];

$steps = [
    ['num' => '01', 'title' => 'Диалог', 'text' => 'Начинаем не с процедуры, а с вашего запроса, привычек и того, что сейчас беспокоит.'],
    ['num' => '02', 'title' => 'Диагностика', 'text' => 'Оцениваем состояние кожи и ограничения. Исключаем лишнее, определяем приоритеты.'],
    ['num' => '03', 'title' => 'Протокол', 'text' => 'Подбираем процедуру, частоту и домашний уход без универсальных схем «для всех».'],
    ['num' => '04', 'title' => 'Динамика', 'text' => 'Смотрим реакцию кожи и меняем программу только тогда, когда это действительно нужно.'],
];

$reviews = [
    ['text' => 'Очень понравилось, что мне не пытались продать всё сразу. Разобрали уход, убрали лишнее и уже после первой процедуры кожа выглядела спокойнее.', 'name' => 'Мария', 'meta' => 'уход + консультация'],
    ['text' => 'Тот редкий случай, когда чувствуешь не поток клиентов, а внимание к деталям. Всё объяснили до процедуры и дали понятный план после.', 'name' => 'Екатерина', 'meta' => 'персональная программа'],
    ['text' => 'Люблю естественный результат. Здесь именно такой подход: аккуратно, современно и без ощущения, что лицо должно стать «другим».', 'name' => 'Анна', 'meta' => 'лифтинг-уход'],
];

$faqs = [
    ['q' => 'Нужно ли заранее выбирать процедуру?', 'a' => 'Нет. Если вы не уверены, лучше начать с консультации. Конкретный протокол определяется после оценки состояния кожи, запроса и возможных ограничений.'],
    ['q' => 'Можно ли прийти только на разбор домашнего ухода?', 'a' => 'Да. Консультация может быть самостоятельной услугой: разберём текущие средства, последовательность и то, что действительно стоит изменить.'],
    ['q' => 'Как часто нужны процедуры?', 'a' => 'Частота зависит от задачи, конкретного протокола и реакции кожи. На сайте намеренно нет универсального обещания «курс из N процедур для всех».'],
    ['q' => 'Есть ли противопоказания?', 'a' => 'Да, у части процедур есть ограничения и противопоказания. Перед записью на конкретную процедуру они уточняются индивидуально.'],
    ['q' => 'Можно ли записаться онлайн?', 'a' => 'Да. В рабочей версии сюда подключается ссылка специалиста на онлайн-запись, мессенджер или CRM.'],
];

$bookingUrl = trim((string)($site['booking_url'] ?? ''));
$phone = trim((string)($site['phone'] ?? ''));
$telegram = trim((string)($site['telegram'] ?? ''));
$address = trim((string)($site['address'] ?? ''));
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#12100f">
    <meta name="robots" content="<?= e((string)$site['seo']['robots']) ?>">
    <meta name="description" content="<?= e((string)$site['seo']['description']) ?>">
    <title><?= e((string)$site['seo']['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>
<div class="grain" aria-hidden="true"></div>
<div class="scroll-progress" aria-hidden="true"><span></span></div>

<header class="site-header" id="top">
    <div class="container header-inner">
        <a class="brand" href="#top" aria-label="<?= e((string)$site['site_name']) ?> — на главную">
            <span class="brand-mark">É</span>
            <span class="brand-copy">
                <strong><?= e((string)$site['site_name']) ?></strong>
                <small>skin atelier</small>
            </span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span><b>Меню</b></button>
        <nav class="main-nav" id="main-nav" aria-label="Основная навигация">
            <a href="#services">Процедуры</a>
            <a href="#method">Подход</a>
            <a href="#selector">Подбор ухода</a>
            <a href="#about">Специалист</a>
            <a href="#reviews">Отзывы</a>
            <a href="#faq">FAQ</a>
        </nav>
        <a class="button button-compact button-ghost magnetic" href="#booking">Записаться</a>
    </div>
</header>

<main>
    <section class="hero section-dark" aria-labelledby="hero-title">
        <div class="hero-ambient hero-ambient-a"></div>
        <div class="hero-ambient hero-ambient-b"></div>
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <p class="eyebrow">Beauty in quiet confidence · <?= e((string)$site['city']) ?></p>
                <h1 id="hero-title">Точная косметология.<br><em>Тихая роскошь</em><br>вашей кожи.</h1>
                <p class="hero-lead"><?= e((string)$site['description']) ?></p>
                <div class="hero-actions">
                    <a class="button button-light magnetic" href="#booking">Записаться на консультацию <span>↗</span></a>
                    <a class="round-link" href="#services" aria-label="Смотреть процедуры"><span>↓</span></a>
                </div>
                <div class="hero-proof" aria-label="Преимущества">
                    <div><strong>01</strong><span>Персональный<br>протокол</span></div>
                    <div><strong>02</strong><span>Бережная<br>эстетика</span></div>
                    <div><strong>03</strong><span>Понятное<br>сопровождение</span></div>
                </div>
            </div>

            <div class="hero-art reveal" data-parallax="0.045" aria-hidden="true">
                <div class="hero-frame">
                    <div class="hero-sculpture">
                        <div class="sculpture-halo"></div>
                        <div class="sculpture-face">
                            <span class="face-highlight"></span>
                            <span class="face-shadow"></span>
                            <span class="face-line line-eye"></span>
                            <span class="face-line line-nose"></span>
                            <span class="face-line line-lip"></span>
                        </div>
                        <div class="sculpture-neck"></div>
                    </div>
                    <div class="glass-orbit orbit-one"></div>
                    <div class="glass-orbit orbit-two"></div>
                    <span class="art-label art-label-top">skin / science / care</span>
                    <span class="art-label art-label-bottom">01 — natural result</span>
                </div>
                <div class="floating-card">
                    <span>philosophy</span>
                    <strong>Less correction.<br>More harmony.</strong>
                </div>
            </div>
        </div>
        <div class="hero-marquee" aria-hidden="true">
            <div class="marquee-track">
                <span>SKIN HEALTH</span><i>✦</i><span>PERSONAL CARE</span><i>✦</i><span>NATURAL RESULT</span><i>✦</i><span>SKIN HEALTH</span><i>✦</i><span>PERSONAL CARE</span><i>✦</i><span>NATURAL RESULT</span><i>✦</i>
            </div>
        </div>
    </section>

    <section class="manifesto section reveal">
        <div class="container manifesto-grid">
            <p class="section-kicker">01 / Philosophy</p>
            <div>
                <h2>Кожа не обязана быть идеальной.<br><em>Она должна выглядеть здоровой, ухоженной и вашей.</em></h2>
                <p>Мы не строим уход вокруг модной процедуры. Сначала — состояние кожи и цель, потом — инструмент. Такой подход делает результат естественным, а путь к нему — понятным.</p>
            </div>
        </div>
    </section>

    <section class="services section" id="services">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="section-kicker">02 / Menu</p>
                    <h2>Процедуры, которые<br><em>работают на качество кожи</em></h2>
                </div>
                <p>Демонстрационный прайс. В реальном проекте названия, стоимость и противопоказания заменяются на данные конкретного специалиста.</p>
            </div>
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                    <article class="service-card reveal" data-service="<?= e($service['tag']) ?>">
                        <div class="service-top"><span><?= e($service['num']) ?></span><span class="service-price"><?= e($service['price']) ?></span></div>
                        <div class="service-icon" aria-hidden="true"><span></span></div>
                        <div class="service-body">
                            <h3><?= e($service['title']) ?></h3>
                            <p><?= e($service['text']) ?></p>
                        </div>
                        <a href="#booking" class="service-link">Обсудить процедуру <span>↗</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="method section section-dark" id="method">
        <div class="method-glow"></div>
        <div class="container method-grid">
            <div class="method-copy reveal">
                <p class="section-kicker">03 / Method</p>
                <h2>Сначала понять кожу.<br><em>Потом — выбирать процедуру.</em></h2>
                <p>Никаких «обязательных» курсов до знакомства. План строится вокруг реального состояния кожи и может меняться по динамике.</p>
                <div class="method-stat"><strong data-count="4">4</strong><span>этапа до понятного<br>персонального плана</span></div>
            </div>
            <div class="steps">
                <?php foreach ($steps as $step): ?>
                    <article class="step reveal">
                        <span class="step-num"><?= e($step['num']) ?></span>
                        <div><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></div>
                        <span class="step-dot" aria-hidden="true"></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="selector section" id="selector">
        <div class="container selector-grid">
            <div class="selector-intro reveal">
                <p class="section-kicker">04 / Skin focus</p>
                <h2>Что сейчас хочется<br><em>изменить в ощущении кожи?</em></h2>
                <p>Небольшой интерактив показывает, как сайт может мягко переводить клиента от запроса к консультации, не ставя диагнозов онлайн.</p>
            </div>
            <div class="skin-selector reveal" data-skin-selector>
                <div class="selector-tabs" role="tablist" aria-label="Запрос к коже">
                    <button class="selector-tab is-active" type="button" role="tab" aria-selected="true" data-focus="comfort">Сухость / дискомфорт</button>
                    <button class="selector-tab" type="button" role="tab" aria-selected="false" data-focus="tone">Тусклый тон</button>
                    <button class="selector-tab" type="button" role="tab" aria-selected="false" data-focus="texture">Неровная текстура</button>
                    <button class="selector-tab" type="button" role="tab" aria-selected="false" data-focus="age">Усталый вид</button>
                </div>
                <div class="selector-result" aria-live="polite">
                    <span class="result-label">Возможный первый шаг</span>
                    <h3 data-result-title>Диагностика + восстановление барьера</h3>
                    <p data-result-text>Начать с оценки домашнего ухода и деликатного протокола на увлажнение и комфорт. Интенсивные процедуры — только после понимания реакции кожи.</p>
                    <div class="result-tags" data-result-tags><span>диагностика</span><span>увлажнение</span><span>home care</span></div>
                    <a href="#booking" class="button button-dark magnetic">Получить персональный план <span>↗</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="bento section-soft">
        <div class="container bento-grid">
            <article class="bento-card bento-large reveal">
                <div class="bento-copy"><p class="section-kicker">Skin intelligence</p><h2>Ничего лишнего.<br><em>Только то, что нужно коже сейчас.</em></h2></div>
                <div class="skin-sphere" data-parallax="0.025" aria-hidden="true"><span></span><i></i></div>
            </article>
            <article class="bento-card bento-number reveal"><strong>01</strong><span>специалист<br>на всём маршруте</span></article>
            <article class="bento-card bento-quote reveal"><span>“</span><p>Красивый результат не должен выглядеть как попытка стать кем-то другим.</p></article>
            <article class="bento-card bento-detail reveal"><div class="detail-lines"></div><p>Протоколы подбираются по показаниям и состоянию кожи, а не по трендам.</p></article>
        </div>
    </section>

    <section class="about section" id="about">
        <div class="container about-grid">
            <div class="about-art reveal">
                <div class="portrait-art" aria-hidden="true">
                    <div class="portrait-halo"></div>
                    <div class="portrait-shape"><span></span></div>
                    <p>YOUR PORTRAIT<br>HERE</p>
                </div>
                <div class="about-seal"><span>personal</span><strong>care</strong><span>since · 20XX</span></div>
            </div>
            <div class="about-copy reveal">
                <p class="section-kicker">05 / Expert</p>
                <h2><?= e((string)$site['specialist']) ?></h2>
                <p class="about-lead">В рабочей версии здесь — реальный специалист: образование, сертификаты, опыт и личный подход. Сейчас блок собран как премиальная демо-подача без выдуманных регалий.</p>
                <div class="about-list">
                    <div><span>01</span><strong>Эстетика без перегиба</strong><p>Цель — качество и ухоженный вид кожи, а не эффект «другого лица».</p></div>
                    <div><span>02</span><strong>Осознанный выбор</strong><p>Каждый этап объясняется простым языком: что делаем, зачем и чего ожидать.</p></div>
                    <div><span>03</span><strong>Системный подход</strong><p>Процедуры и домашний уход работают как единая программа, а не как случайный набор услуг.</p></div>
                </div>
                <a href="#booking" class="text-arrow">Познакомиться на консультации <span>↗</span></a>
            </div>
        </div>
    </section>

    <section class="reviews section" id="reviews">
        <div class="container">
            <div class="section-heading reviews-heading reveal">
                <div><p class="section-kicker">06 / Reviews</p><h2>Спокойствие до процедуры.<br><em>Уверенность после.</em></h2></div>
                <p>Демонстрационные отзывы отмечены как пример. Перед запуском заменяются на подтверждённые отзывы реальных клиентов.</p>
            </div>
            <div class="reviews-track">
                <?php foreach ($reviews as $index => $review): ?>
                    <article class="review-card reveal">
                        <div class="review-head"><span>0<?= $index + 1 ?></span><span>DEMO REVIEW</span></div>
                        <blockquote>“<?= e($review['text']) ?>”</blockquote>
                        <div class="review-author"><strong><?= e($review['name']) ?></strong><span><?= e($review['meta']) ?></span></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="faq section" id="faq">
        <div class="container faq-grid">
            <div class="faq-intro reveal">
                <p class="section-kicker">07 / FAQ</p>
                <h2>Вопросы,<br><em>которые лучше задать заранее</em></h2>
                <p>Честный FAQ уменьшает тревогу перед первым визитом и помогает клиенту принять решение без давления.</p>
            </div>
            <div class="accordion">
                <?php foreach ($faqs as $i => $faq): ?>
                    <details class="faq-item reveal" <?= $i === 0 ? 'open' : '' ?>>
                        <summary><span class="faq-number">0<?= $i + 1 ?></span><strong><?= e($faq['q']) ?></strong><i>+</i></summary>
                        <p><?= e($faq['a']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="booking section-dark" id="booking">
        <div class="booking-orb" aria-hidden="true"></div>
        <div class="container booking-grid">
            <div class="booking-copy reveal">
                <p class="section-kicker">08 / Appointment</p>
                <h2>Начнём с вашего<br><em>реального запроса</em></h2>
                <p>Оставьте способ связи — в клиентской версии форма будет отправляться в CRM, Telegram или на почту. Сейчас это безопасная демонстрация интерфейса без передачи данных.</p>
                <div class="booking-meta"><span>Ответим в рабочее время</span><span>Без навязчивых рассылок</span></div>
            </div>
            <div class="booking-panel reveal">
                <?php if ($bookingUrl !== ''): ?>
                    <a class="button button-light button-full" href="<?= e($bookingUrl) ?>" target="_blank" rel="noopener">Перейти к онлайн-записи <span>↗</span></a>
                <?php else: ?>
                    <form class="demo-form" data-demo-form novalidate>
                        <label><span>Ваше имя</span><input type="text" name="name" autocomplete="name" placeholder="Например, Анна" required></label>
                        <label><span>Телефон или Telegram</span><input type="text" name="contact" placeholder="+7 900 000-00-00" required></label>
                        <label><span>Что хочется улучшить?</span><textarea name="message" rows="3" placeholder="Коротко опишите ваш запрос"></textarea></label>
                        <button class="button button-light button-full magnetic" type="submit">Записаться на консультацию <span>↗</span></button>
                        <small>Демо-форма: данные никуда не отправляются.</small>
                    </form>
                    <div class="form-success" data-form-success hidden><span>✓</span><h3>Интерфейс работает</h3><p>В реальном проекте здесь будет отправка заявки специалисту.</p><button type="button" data-form-reset>Вернуться к форме</button></div>
                <?php endif; ?>
                <?php if ($phone !== '' || $telegram !== '' || $address !== ''): ?>
                    <div class="direct-contacts">
                        <?php if ($phone !== ''): ?><a href="tel:<?= e((string)preg_replace('/[^+0-9]/', '', $phone)) ?>"><span>Телефон</span><strong><?= e($phone) ?></strong></a><?php endif; ?>
                        <?php if ($telegram !== ''): ?><a href="<?= e($telegram) ?>" target="_blank" rel="noopener"><span>Telegram</span><strong>Написать ↗</strong></a><?php endif; ?>
                        <?php if ($address !== ''): ?><div><span>Адрес</span><strong><?= e($address) ?></strong></div><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-top">
        <a class="brand footer-brand" href="#top"><span class="brand-mark">É</span><span class="brand-copy"><strong><?= e((string)$site['site_name']) ?></strong><small>skin atelier</small></span></a>
        <p>Демонстрационный проект сайта косметолога.<br>Контент, цены и контакты заменяются на реальные данные клиента.</p>
        <nav aria-label="Навигация в подвале"><a href="#services">Процедуры</a><a href="#about">Специалист</a><a href="#faq">FAQ</a><a href="#booking">Запись</a></nav>
    </div>
    <div class="container footer-bottom"><span>© <?= date('Y') ?> <?= e((string)$site['site_name']) ?></span><span>Информация на сайте не заменяет очную консультацию специалиста.</span><a href="#top">Наверх ↑</a></div>
</footer>

<div class="mobile-booking"><a class="button button-dark button-full" href="#booking">Записаться <span>↗</span></a></div>
<script src="assets/js/app.js?v=2" defer></script>
</body>
</html>
