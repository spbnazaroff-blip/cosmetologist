<?php

declare(strict_types=1);

$site = require __DIR__ . '/config.php';
require __DIR__ . '/includes/content.php';
require __DIR__ . '/includes/layout.php';

$services = content_featured('services', 3);
$cases = content_featured('cases', 3);
$articles = content_featured('articles', 3);
$videos = content_featured('videos', 2);

$faqs = [
    ['Нужно ли заранее выбирать процедуру?', 'Нет. Если вы не уверены, лучше начать с консультации: протокол определяется после оценки состояния кожи, вашего запроса и возможных ограничений.'],
    ['Можно ли прийти только на разбор домашнего ухода?', 'Да. Консультация может быть самостоятельной услугой — без обязательного курса процедур.'],
    ['Почему на сайте указаны цены «от»?', 'Финальная стоимость зависит от конкретного протокола и объёма работы. На отдельной странице прайса видно базовую стоимость каждой услуги.'],
    ['Можно ли посмотреть результаты?', 'Да. Для этого есть отдельный раздел с кейсами. Перед запуском демо-кейсы заменяются на реальные фото и данные с согласия клиента.'],
];

render_header($site);
?>
<main>
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy reveal">
      <p class="eyebrow">Эстетическая косметология · <?= esc((string)$site['city']) ?></p>
      <h1>Точная косметология.<br><em>Тихая роскошь</em><br>вашей кожи.</h1>
      <p class="hero-lead"><?= esc((string)$site['description']) ?></p>
      <div class="hero-actions"><a class="button button-light" href="#booking">Записаться на консультацию <span>↗</span></a><a class="button button-outline" href="/services.php">Посмотреть услуги</a></div>
      <div class="hero-proof"><div><strong>01</strong><span>Персональный<br>маршрут</span></div><div><strong>02</strong><span>Понятная<br>логика ухода</span></div><div><strong>03</strong><span>Естественная<br>эстетика</span></div></div>
    </div>
    <div class="hero-art reveal" data-parallax="14" aria-hidden="true"><div class="hero-frame"><div class="art-face"></div><div class="art-neck"></div><div class="art-orbit"></div></div><div class="floating-card"><span>philosophy</span><strong>Less correction.<br>More harmony.</strong></div></div>
  </div>
  <div class="marquee"><div class="marquee-track"><span>SKIN HEALTH</span><i>✦</i><span>PERSONAL CARE</span><i>✦</i><span>NATURAL RESULT</span><i>✦</i><span>SKIN HEALTH</span><i>✦</i><span>PERSONAL CARE</span><i>✦</i><span>NATURAL RESULT</span><i>✦</i></div></div>
</section>

<section class="section statement"><div class="container statement-grid reveal"><p class="kicker">01 / Philosophy</p><div><h2>Не «больше процедур», а <em>точнее подобранный уход.</em></h2><p class="lead">Сначала состояние кожи и цель, потом — инструмент. Сайт построен вокруг консультационного маршрута, а не вокруг давления «купить процедуру сейчас».</p></div></div></section>

<section class="section" id="services"><div class="container"><div class="section-head reveal"><div><p class="kicker">02 / Services</p><h2>Ключевые процедуры.<br><em>Полная информация — внутри.</em></h2></div><p>На главной показываем только основные направления. Полный каталог и отдельные описания вынесены на страницу услуг, а стоимость — в прозрачный прайс.</p></div><div class="cards">
<?php foreach ($services as $service): ?><article class="card reveal"><div class="card-meta"><span><?= esc((string)$service['category']) ?></span><span><?= esc((string)$service['price']) ?></span></div><div class="service-tile-image"><img loading="lazy" src="<?=esc(media_cover($service,'services'))?>" alt="<?=esc(media_alt($service,(string)$service['title']))?>"></div><div class="card-body"><h3><?= esc((string)$service['title']) ?></h3><p><?= esc((string)$service['summary']) ?></p></div><a class="card-foot" href="/service.php?slug=<?= rawurlencode((string)$service['slug']) ?>"><span>Подробнее</span><span>↗</span></a></article><?php endforeach; ?>
</div><div style="margin-top:30px;display:flex;gap:12px;flex-wrap:wrap"><a class="button button-dark" href="/services.php">Все услуги</a><a class="button button-outline" href="/price.php">Открыть прайс</a></div></div></section>

<section class="section section-dark concern-section" id="selector"><div class="container concern-grid"><div class="concern-copy reveal"><p class="kicker">03 / Skin focus</p><h2>Начать можно<br><em>не с названия процедуры.</em></h2><p class="lead">Выберите то, что сейчас беспокоит. Блок не ставит диагноз — он показывает возможную логику первого шага и ведёт к консультации.</p></div><div class="concern-panel reveal" data-concern-selector><div class="concern-tabs"><button class="concern-tab is-active" type="button" data-focus="comfort">Сухость / дискомфорт</button><button class="concern-tab" type="button" data-focus="tone">Тусклый тон</button><button class="concern-tab" type="button" data-focus="texture">Неровная текстура</button><button class="concern-tab" type="button" data-focus="tired">Усталый вид</button></div><div class="concern-result"><small>Возможный первый шаг</small><h3 data-result-title>Диагностика + восстановление барьера</h3><p data-result-text>Начинаем с разбора ухода и мягкого протокола на увлажнение и комфорт. Интенсивные процедуры — только после оценки реакции кожи.</p><a class="button button-light" href="#booking">Получить персональный план <span>↗</span></a></div></div></div></section>

<section class="section" id="cases"><div class="container"><div class="section-head reveal"><div><p class="kicker">04 / Results</p><h2>Не просто «до / после».<br><em>Контекст результата.</em></h2></div><p>В каждом кейсе показываем исходный запрос, протокол, срок и итог. Это полезнее безымянной галереи и вызывает больше доверия.</p></div><div class="case-grid">
<?php foreach ($cases as $case): ?><article class="case-card reveal"><div class="before-after" style="min-height:300px"><figure><img loading="lazy" src="<?=esc(media_case_image($case,'before'))?>" alt="<?=esc(media_alt($case,'До — '.(string)$case['title']))?>"><figcaption>до</figcaption></figure><figure><img loading="lazy" src="<?=esc(media_case_image($case,'after'))?>" alt="<?=esc(media_alt($case,'После — '.(string)$case['title']))?>"><figcaption>после</figcaption></figure></div><div class="case-copy"><span class="case-period"><?= esc((string)$case['period']) ?></span><h3><?= esc((string)$case['title']) ?></h3><p><?= esc((string)$case['result']) ?></p><a class="text-link" href="/case.php?slug=<?= rawurlencode((string)$case['slug']) ?>">Смотреть кейс <span>↗</span></a></div></article><?php endforeach; ?>
</div><p class="disclaimer">Демонстрационные кейсы и временные фото для визуализации. В рабочей версии публикуются только реальные материалы с согласия клиента. Результат индивидуален.</p><div style="margin-top:25px"><a class="button button-outline" href="/cases.php">Все результаты</a></div></div></section>

<section class="section section-soft" id="about"><div class="container statement-grid reveal"><p class="kicker">05 / Expert</p><div><h2><?= esc((string)$site['specialist']) ?><br><em>Лицо бренда — не прячем.</em></h2><p class="lead">В финальной версии здесь будут реальные образование, опыт, сертификаты и профессиональная специализация. Мы не придумываем регалии для демо — доверие строится на проверяемых фактах.</p><a class="text-link" href="#booking">Записаться на знакомство <span>↗</span></a></div></div></section>

<section class="section" id="blog"><div class="container"><div class="section-head reveal"><div><p class="kicker">06 / Journal</p><h2>Блог, который<br><em>работает на экспертность и SEO.</em></h2></div><p>Не новости ради новостей, а полезные статьи: домашний уход, подготовка, восстановление, ответы на частые вопросы и разборы процедур.</p></div><?php if ($articles): $first = array_shift($articles); ?><div class="editorial-grid"><article class="article-feature reveal" style="background-image:linear-gradient(180deg,rgba(15,12,10,.18),rgba(15,12,10,.78)),url('<?=esc(media_cover($first,'articles'))?>')"><span class="article-meta"><?= esc((string)$first['category']) ?> · <?= esc((string)$first['read_time']) ?></span><h3><?= esc((string)$first['title']) ?></h3><p><?= esc((string)$first['excerpt']) ?></p><a class="text-link" href="/article.php?slug=<?= rawurlencode((string)$first['slug']) ?>">Читать статью <span>↗</span></a></article><div class="article-list"><?php foreach ($articles as $article): ?><article class="article-small reveal"><span class="article-meta"><?= esc((string)$article['category']) ?> · <?= esc((string)$article['read_time']) ?></span><h3><?= esc((string)$article['title']) ?></h3><p><?= esc((string)$article['excerpt']) ?></p><a class="text-link" href="/article.php?slug=<?= rawurlencode((string)$article['slug']) ?>">Читать <span>↗</span></a></article><?php endforeach; ?></div></div><?php endif; ?><div style="margin-top:28px"><a class="button button-outline" href="/blog.php">Все статьи</a></div></div></section>

<section class="section section-soft" id="video"><div class="container"><div class="section-head reveal"><div><p class="kicker">07 / Video hub</p><h2>Видео — там, где<br><em>эксперта хочется услышать.</em></h2></div><p>В админке достаточно вставить ссылку на VK Видео или RuTube. Видео автоматически появляется в этом разделе без ручного редактирования страницы.</p></div><div class="video-grid">
<?php foreach ($videos as $video): $embed = video_embed_url((string)$video['url']); ?><article class="video-card reveal"><div class="video-frame"><?php if ($embed): ?><iframe src="<?= esc($embed) ?>" loading="lazy" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe><?php else: ?><div class="video-poster"><img loading="lazy" src="<?=esc(media_cover($video,'videos'))?>" alt="<?=esc(media_alt($video,(string)$video['title']))?>"><span class="video-play">▶</span></div><?php endif; ?></div><div class="video-copy"><h3><?= esc((string)$video['title']) ?></h3><p><?= esc((string)$video['description']) ?></p></div></article><?php endforeach; ?>
</div><div style="margin-top:28px"><a class="button button-outline" href="/videos.php">Вся видеотека</a></div></div></section>

<section class="section"><div class="container"><div class="trust-strip reveal"><div class="trust-item"><strong>01</strong><span>Понятный путь<br>от запроса к записи</span></div><div class="trust-item"><strong>02</strong><span>Отдельные услуги<br>и прозрачный прайс</span></div><div class="trust-item"><strong>03</strong><span>Кейсы с контекстом,<br>не просто фото</span></div><div class="trust-item"><strong>04</strong><span>Статьи и видео<br>для доверия и SEO</span></div></div></div></section>

<section class="section section-soft" id="faq"><div class="container faq-grid"><div class="faq-intro reveal"><p class="kicker">08 / FAQ</p><h2>Вопросы,<br><em>которые лучше задать заранее.</em></h2><p class="lead">Хороший FAQ уменьшает тревогу перед первым визитом и снимает типовые возражения без давления.</p></div><div class="accordion"><?php foreach ($faqs as $i => $faq): ?><details class="faq-item reveal" <?= $i === 0 ? 'open' : '' ?>><summary><span>0<?= $i + 1 ?></span><strong><?= esc($faq[0]) ?></strong><i>+</i></summary><p><?= esc($faq[1]) ?></p></details><?php endforeach; ?></div></div></section>

<section class="section section-dark cta" id="booking"><div class="container cta-grid"><div class="reveal"><p class="kicker">09 / Appointment</p><h2>Начнём с вашего<br><em>реального запроса.</em></h2><p class="lead">Сейчас форма демонстрационная. В рабочем проекте её можно подключить к CRM, Telegram, почте или сервису онлайн-записи.</p></div><div class="cta-panel reveal"><form class="demo-form" data-demo-form><label><span>Ваше имя</span><input name="name" placeholder="Например, Анна" required></label><label><span>Телефон или Telegram</span><input name="contact" placeholder="+7 900 000-00-00" required></label><label><span>Ваш запрос</span><textarea name="message" rows="3" placeholder="Коротко опишите, что хочется улучшить"></textarea></label><button class="button button-light button-full" type="submit">Записаться <span>↗</span></button><small>Демо-форма: данные никуда не отправляются.</small></form><div class="form-success" data-form-success hidden><h3>Интерфейс работает</h3><p>После подключения CRM или мессенджера заявка будет уходить специалисту.</p><button class="button button-outline" type="button" data-form-reset>Вернуться</button></div></div></div></section>
</main>
<?php render_footer($site); ?>