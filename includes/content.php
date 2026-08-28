<?php

declare(strict_types=1);

$storageOverride = trim((string)getenv('COSMETOLOGIST_STORAGE'));
$siteRoot = dirname(__DIR__);
$instance = preg_replace('/[^a-z0-9.-]+/i', '-', basename($siteRoot)) ?: 'cosmetologist';
$defaultStorageRoot = dirname(__DIR__, 3) . '/cosmetologist-storage/' . $instance;
define('CONTENT_STORAGE', $storageOverride !== '' ? $storageOverride : $defaultStorageRoot);

function content_defaults(string $type): array
{
    $defaults = [
        'services' => [
            ['id'=>'diagnostics','slug'=>'diagnostika-kozhi','title'=>'Диагностика кожи','subtitle'=>'Точная отправная точка','price'=>'от 2 500 ₽','duration'=>'45–60 мин','category'=>'Консультация','summary'=>'Оценка текущего состояния кожи, домашнего ухода и целей с персональной стратегией.','body'=>'Начинаем с разговора и оценки текущего состояния кожи. Разбираем домашний уход, привычки и цели. После консультации вы получаете понятный план: что оставить, что изменить и какие процедуры действительно имеют смысл.','featured'=>true],
            ['id'=>'clean','slug'=>'glubokoe-ochishchenie','title'=>'Глубокое очищение','subtitle'=>'Чистота без агрессии','price'=>'от 4 900 ₽','duration'=>'60–90 мин','category'=>'Уход','summary'=>'Деликатное очищение и обновление с подбором интенсивности под состояние кожи.','body'=>'Протокол подбирается индивидуально и может включать подготовку, очищение, деликатное обновление и завершающий уход. Цель — не «очистить до скрипа», а сохранить комфорт и защитный барьер кожи.','featured'=>true],
            ['id'=>'glow','slug'=>'uvlazhnenie-glow','title'=>'Увлажнение & glow','subtitle'=>'Комфорт и естественное сияние','price'=>'от 5 500 ₽','duration'=>'60 мин','category'=>'Уход','summary'=>'Протокол на восстановление барьера, комфорт, ровную текстуру и естественное сияние.','body'=>'Уход направлен на восстановление ощущения комфорта и визуальной свежести кожи. Конкретные средства и этапы выбираются после оценки состояния кожи и возможной чувствительности.','featured'=>true],
            ['id'=>'renew','slug'=>'obnovlenie-kozhi','title'=>'Обновление кожи','subtitle'=>'Ровнее текстура и тон','price'=>'от 5 900 ₽','duration'=>'45–70 мин','category'=>'Обновление','summary'=>'Мягкие программы обновления кожи с контролем интенсивности и рекомендациями по восстановлению.','body'=>'Интенсивность обновления подбирается под сезон, состояние кожи и привычный домашний уход. После процедуры клиент получает рекомендации по восстановлению и защите кожи.','featured'=>false],
            ['id'=>'lift','slug'=>'lifting-uhod','title'=>'Лифтинг-уход','subtitle'=>'Свежий и отдохнувший вид','price'=>'от 6 500 ₽','duration'=>'60–90 мин','category'=>'Уход','summary'=>'Комплексный эстетический уход с акцентом на тонус и качество кожи.','body'=>'Программа сочетает этапы ухода, которые помогают коже выглядеть более свежей и ухоженной. Объём и интенсивность подбираются индивидуально без обещаний медицинского результата.','featured'=>false],
            ['id'=>'course','slug'=>'personalnyj-kurs','title'=>'Персональный курс','subtitle'=>'Системный маршрут','price'=>'индивидуально','duration'=>'по плану','category'=>'Программа','summary'=>'Последовательная программа процедур и домашнего ухода с отслеживанием динамики.','body'=>'Курс формируется только после консультации. Частота и набор процедур могут меняться по реакции кожи. Важная часть программы — домашний уход и понятные контрольные точки.','featured'=>false],
        ],
        'articles' => [
            ['id'=>'barrier','slug'=>'kak-ponyat-chto-narushen-barer-kozhi','title'=>'Как понять, что защитный барьер кожи перегружен','excerpt'=>'Стянутость, реактивность и желание постоянно менять уход не всегда означают, что коже нужно больше активов.','body'=>'Когда кожа становится чувствительной, стянутой или непредсказуемо реагирует на привычные средства, первое желание — добавить ещё одну активную сыворотку. Но иногда полезнее упростить уход.\n\nЗащитный барьер кожи участвует в удержании влаги и снижает воздействие внешних факторов. Его состояние зависит от множества причин: слишком интенсивного очищения, частого использования активов, климата и индивидуальной чувствительности.\n\nЕсли дискомфорт сохраняется, лучше не собирать диагноз по интернету, а обсудить состояние кожи со специалистом и при необходимости с врачом.','date'=>'2026-08-20','category'=>'Уход за кожей','read_time'=>'5 мин','featured'=>true],
            ['id'=>'routine','slug'=>'minimalnyj-domashnij-uhod','title'=>'Минимальный домашний уход: что действительно является базой','excerpt'=>'База не обязана состоять из десяти банок. Важнее последовательность, переносимость и регулярность.','body'=>'Универсального набора средств для всех не существует, но логика базового ухода обычно проста: мягкое очищение, увлажнение и защита от солнца по показаниям и сезону.\n\nАктивные компоненты лучше добавлять не потому, что они популярны, а под конкретную задачу. Чем сложнее схема, тем важнее понимать, что делает каждый продукт.\n\nЕсли кожа реагирует на уход раздражением, стоит пересмотреть интенсивность и сочетание средств.','date'=>'2026-08-12','category'=>'Домашний уход','read_time'=>'4 мин','featured'=>true],
            ['id'=>'consult','slug'=>'zachem-konsultaciya-pered-proceduroj','title'=>'Зачем начинать с консультации, если уже знаете название процедуры','excerpt'=>'Одинаковый запрос у двух людей не означает одинаковый протокол.','body'=>'Название процедуры — это инструмент, а не цель. Перед её выбором важно понять состояние кожи, текущий уход, противопоказания и ожидаемый результат.\n\nИногда после консультации первоначальный выбор подтверждается. Иногда становится понятно, что начать лучше с более мягкого этапа. Это нормальная часть персонального подхода.','date'=>'2026-08-04','category'=>'Косметология','read_time'=>'3 мин','featured'=>false],
        ],
        'cases' => [
            ['id'=>'case-comfort','slug'=>'vosstanovlenie-komforta','title'=>'Возвращение комфорта коже','request'=>'Сухость, чувство стянутости и перегруженный домашний уход.','protocol'=>'Разбор ухода → мягкий восстанавливающий протокол → упрощение домашней схемы.','result'=>'Кожа стала визуально спокойнее и комфортнее по ощущениям клиента.','period'=>'4 недели','before_image'=>'','after_image'=>'','featured'=>true],
            ['id'=>'case-tone','slug'=>'rovnyj-ton-i-siyanie','title'=>'Более ровный тон и свежий вид','request'=>'Тусклый тон и ощущение «уставшей» кожи.','protocol'=>'Диагностика → курс мягких уходовых процедур → корректировка домашнего ухода.','result'=>'Более свежий, ухоженный вид и визуально ровная текстура кожи.','period'=>'6 недель','before_image'=>'','after_image'=>'','featured'=>true],
            ['id'=>'case-texture','slug'=>'rabota-s-teksturoj','title'=>'Деликатная работа с текстурой','request'=>'Неровная текстура и желание интенсивно «обновить» кожу.','protocol'=>'Постепенное обновление с контролем реакции кожи и обязательным восстановлением.','result'=>'Более аккуратная текстура без гонки за максимально агрессивным протоколом.','period'=>'8 недель','before_image'=>'','after_image'=>'','featured'=>false],
        ],
        'videos' => [
            ['id'=>'video-demo-1','title'=>'Как проходит первая консультация','description'=>'Пример карточки видео. В админке сюда вставляется ссылка VK Видео или RuTube.','url'=>'','date'=>'2026-08-18','featured'=>true],
            ['id'=>'video-demo-2','title'=>'Три ошибки в домашнем уходе','description'=>'Видео может быть частью образовательного раздела и дополнительно усиливать экспертность специалиста.','url'=>'','date'=>'2026-08-10','featured'=>true],
        ],
    ];

    return $defaults[$type] ?? [];
}

function content_file(string $type): string
{
    return CONTENT_STORAGE . '/' . preg_replace('/[^a-z0-9_-]/i', '', $type) . '.json';
}

function load_content(string $type): array
{
    $file = content_file($type);
    if (!is_file($file)) {
        return content_defaults($type);
    }
    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return content_defaults($type);
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : content_defaults($type);
}

function save_content(string $type, array $items): bool
{
    if (!is_dir(CONTENT_STORAGE) && !mkdir(CONTENT_STORAGE, 0775, true) && !is_dir(CONTENT_STORAGE)) {
        return false;
    }
    $json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return $json !== false && file_put_contents(content_file($type), $json, LOCK_EX) !== false;
}

function content_by_slug(string $type, string $slug): ?array
{
    foreach (load_content($type) as $item) {
        if (($item['slug'] ?? '') === $slug) {
            return $item;
        }
    }
    return null;
}

function content_featured(string $type, int $limit = 3): array
{
    $items = array_values(array_filter(load_content($type), static fn(array $item): bool => !empty($item['featured'])));
    return array_slice($items, 0, $limit);
}

function format_date_ru(string $date): string
{
    $ts = strtotime($date);
    if ($ts === false) return $date;
    $months = [1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня',7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря'];
    return (int)date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function safe_slug(string $value): string
{
    $map = ['а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya'];
    $value = function_exists('mb_strtolower') ? mb_strtolower(trim($value), 'UTF-8') : strtolower(trim($value));
    $value = strtr($value, $map);
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    return trim($value, '-') ?: 'item-' . time();
}

function video_embed_url(string $url): ?string
{
    $url = trim($url);
    if ($url === '') return null;
    $parts = parse_url($url);
    $host = strtolower((string)($parts['host'] ?? ''));
    $path = (string)($parts['path'] ?? '');

    if (in_array($host, ['rutube.ru','www.rutube.ru'], true)) {
        if (preg_match('~/(?:video|shorts)/([a-z0-9-]+)~i', $path, $m)) {
            return 'https://rutube.ru/play/embed/' . rawurlencode($m[1]);
        }
        if (str_contains($path, '/play/embed/')) return $url;
    }

    if (in_array($host, ['vk.com','www.vk.com','vkvideo.ru','www.vkvideo.ru'], true)) {
        if (str_contains($path, 'video_ext.php')) return $url;
        if (preg_match('~video(-?\d+)_(\d+)~', $url, $m)) {
            return 'https://vk.com/video_ext.php?oid=' . rawurlencode($m[1]) . '&id=' . rawurlencode($m[2]) . '&hd=2';
        }
    }
    return null;
}

function render_text(string $text): string
{
    $paragraphs = preg_split('/\R{2,}/u', trim($text)) ?: [];
    $html = [];
    foreach ($paragraphs as $paragraph) {
        $html[] = '<p>' . nl2br(htmlspecialchars(trim($paragraph), ENT_QUOTES, 'UTF-8')) . '</p>';
    }
    return implode("\n", $html);
}
