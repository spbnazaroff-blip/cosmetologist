<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/media-helper.php';

function aesc(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function admin_file(): string { return CONTENT_STORAGE . '/admin.json'; }
function ensure_storage(): bool { return is_dir(CONTENT_STORAGE) || mkdir(CONTENT_STORAGE, 0775, true); }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24)); return (string)$_SESSION['csrf']; }
function csrf_ok(): bool { return isset($_POST['csrf']) && hash_equals((string)($_SESSION['csrf'] ?? ''), (string)$_POST['csrf']); }
function admin_config(): ?array {
    $f = admin_file();
    if (!is_file($f)) return null;
    $d = json_decode((string)file_get_contents($f), true);
    return is_array($d) ? $d : null;
}

$message = '';
$error = '';
$config = admin_config();

if (!$config && ($_POST['action'] ?? '') === 'setup') {
    $user = trim((string)($_POST['username'] ?? 'admin'));
    $pass = (string)($_POST['password'] ?? '');
    if (strlen($pass) < 10) $error = 'Пароль должен содержать минимум 10 символов.';
    elseif (!ensure_storage()) $error = 'Не удалось создать storage.';
    else {
        $payload = [
            'username' => $user !== '' ? $user : 'admin',
            'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
            'created_at' => date(DATE_ATOM),
        ];
        file_put_contents(admin_file(), json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
        $config = $payload;
        $message = 'Администратор создан. Теперь войдите.';
    }
}

if ($config && ($_POST['action'] ?? '') === 'login') {
    $login = trim((string)($_POST['username'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');
    if (hash_equals((string)$config['username'], $login) && password_verify($pass, (string)$config['password_hash'])) {
        $_SESSION['admin'] = true;
        session_regenerate_id(true);
        header('Location: /admin/');
        exit;
    }
    $error = 'Неверный логин или пароль.';
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /admin/');
    exit;
}

$logged = !empty($_SESSION['admin']);

$schemas = [
    'services' => [
        'label' => 'Услуги и цены',
        'fields' => [
            'title'=>'Название','subtitle'=>'Подзаголовок','category'=>'Категория','price'=>'Цена','duration'=>'Длительность',
            'cover_image'=>'Обложка услуги','image_alt'=>'ALT изображения','summary'=>'Короткое описание','body'=>'Полное описание',
            'seo_title'=>'SEO title страницы услуги','meta_description'=>'Meta description','og_image'=>'OG image'
        ],
    ],
    'articles' => [
        'label' => 'Статьи блога',
        'fields' => [
            'title'=>'Заголовок','category'=>'Категория','date'=>'Дата','read_time'=>'Время чтения','cover_image'=>'Обложка статьи',
            'image_alt'=>'ALT изображения','excerpt'=>'Анонс','body'=>'Текст статьи','seo_title'=>'SEO title статьи',
            'meta_description'=>'Meta description','og_image'=>'OG image'
        ],
    ],
    'cases' => [
        'label' => 'Результаты / кейсы',
        'fields' => [
            'title'=>'Название кейса','period'=>'Период','cover_image'=>'Обложка кейса','before_image'=>'Фото до','after_image'=>'Фото после',
            'image_alt'=>'ALT изображений','request'=>'Исходный запрос','protocol'=>'Что делали','result'=>'Результат',
            'seo_title'=>'SEO title кейса','meta_description'=>'Meta description','og_image'=>'OG image'
        ],
    ],
    'videos' => [
        'label' => 'Видео',
        'fields' => [
            'title'=>'Название','date'=>'Дата','url'=>'Ссылка VK Видео или RuTube','cover_image'=>'Обложка видео',
            'image_alt'=>'ALT обложки','description'=>'Описание'
        ],
    ],
];

$type = (string)($_GET['type'] ?? 'articles');
if (!isset($schemas[$type])) $type = 'articles';

if ($logged && $_SERVER['REQUEST_METHOD'] === 'POST' && in_array((string)($_POST['action'] ?? ''), ['save','delete'], true)) {
    if (!csrf_ok()) {
        $error = 'Сессия формы устарела. Обновите страницу.';
    } else {
        $items = load_content($type);
        $id = trim((string)($_POST['id'] ?? ''));

        if (($_POST['action'] ?? '') === 'delete') {
            $items = array_values(array_filter($items, fn($i) => (string)($i['id'] ?? '') !== $id));
            $message = save_content($type, $items) ? 'Запись удалена.' : 'Не удалось сохранить изменения.';
        } else {
            $row = ['id' => $id !== '' ? $id : $type . '-' . bin2hex(random_bytes(4))];
            foreach ($schemas[$type]['fields'] as $field => $label) {
                $row[$field] = trim((string)($_POST[$field] ?? ''));
            }

            try {
                foreach (admin_image_fields() as $imageField) {
                    if (!array_key_exists($imageField, $schemas[$type]['fields'])) continue;
                    $uploaded = admin_upload_image($imageField);
                    if ($uploaded !== null) $row[$imageField] = $uploaded;
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }

            if (in_array($type, ['services','articles','cases'], true)) {
                $row['slug'] = trim((string)($_POST['slug'] ?? '')) ?: safe_slug((string)($row['title'] ?? ''));
            }
            $row['featured'] = isset($_POST['featured']);

            if ($type === 'videos' && $row['url'] !== '' && !video_embed_url((string)$row['url'])) {
                $host = strtolower((string)(parse_url((string)$row['url'], PHP_URL_HOST) ?? ''));
                if (!in_array($host, ['vk.com','www.vk.com','vkvideo.ru','www.vkvideo.ru','rutube.ru','www.rutube.ru'], true)) {
                    $error = 'Разрешены ссылки только VK Видео и RuTube.';
                }
            }

            if ($error === '') {
                $found = false;
                foreach ($items as $k => $item) {
                    if ((string)($item['id'] ?? '') === $row['id']) {
                        $items[$k] = array_merge($item, $row);
                        $found = true;
                        break;
                    }
                }
                if (!$found) $items[] = $row;
                if (save_content($type, $items)) $message = 'Сохранено.';
                else $error = 'Не удалось записать JSON. Проверьте права storage.';
            }
        }
    }
}

$editId = (string)($_GET['edit'] ?? '');
$edit = null;
if ($logged && $editId !== '') {
    foreach (load_content($type) as $item) {
        if ((string)($item['id'] ?? '') === $editId) { $edit = $item; break; }
    }
}

function field_control(string $field, string $label, array $edit): void
{
    $value = (string)($edit[$field] ?? ($field === 'date' ? date('Y-m-d') : ''));
    $textareas = ['body','excerpt','summary','request','protocol','result','description','meta_description'];
    $imageFields = admin_image_fields();

    echo '<label class="field"><span>' . aesc($label) . '</span>';
    if (in_array($field, $textareas, true)) {
        echo '<textarea name="' . aesc($field) . '">' . aesc($value) . '</textarea>';
    } else {
        echo '<input ' . ($field === 'date' ? 'type="date" ' : '') . 'name="' . aesc($field) . '" value="' . aesc($value) . '">';
    }

    if (in_array($field, $imageFields, true)) {
        if ($value !== '') {
            echo '<img class="field-preview" src="' . aesc($value) . '" alt="Превью">';
        }
        echo '<input class="file-input" type="file" name="' . aesc($field) . '_file" accept="image/jpeg,image/png,image/webp">';
        echo '<small>Можно загрузить JPG, PNG или WebP до 8 МБ прямо с телефона/компьютера. Загруженный файл заменит URL выше.</small>';
    }
    echo '</label>';
}
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Управление сайтом</title>
<style>
:root{--bg:#f3efe9;--paper:#fff;--ink:#1d1916;--muted:#746d66;--line:#ddd4ca;--dark:#181411;--accent:#9b775d;--soft:#f7f1eb}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 Arial,sans-serif}a{color:inherit;text-decoration:none}.wrap{width:min(1280px,calc(100% - 28px));margin:30px auto}.auth{max-width:460px;margin:10vh auto;padding:32px;background:#fff;border:1px solid var(--line);border-radius:20px}.auth h1{font:32px Georgia,serif}.field{display:grid;gap:7px;margin:14px 0}.field span{font-size:11px;color:var(--muted);font-weight:700}.field small{color:var(--muted);font-size:11px}input,textarea{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:10px;background:#fff}textarea{min-height:105px;resize:vertical}.file-input{padding:9px;background:var(--soft)}.field-preview{width:100%;max-height:190px;object-fit:cover;border-radius:12px;border:1px solid var(--line);background:#eee}.btn{display:inline-flex;justify-content:center;align-items:center;border:0;border-radius:999px;padding:11px 17px;background:var(--dark);color:#fff;cursor:pointer}.btn.alt{background:#e9e1d8;color:#2c251f}.btn.seo{background:#7d614e}.btn.media{background:#5f7567}.btn.danger{background:#7e312b}.notice{padding:12px 14px;border-radius:10px;margin:12px 0;background:#e6f2e8}.error{background:#f5dfdd}.admin-head{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:24px}.admin-head h1{margin:0;font:34px Georgia,serif}.head-actions{display:flex;gap:7px;flex-wrap:wrap}.tabs{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:22px}.tabs a{padding:9px 13px;border:1px solid var(--line);border-radius:999px}.tabs a.active{background:var(--dark);color:#fff}.grid{display:grid;grid-template-columns:1fr 440px;gap:22px}.panel{background:#fff;border:1px solid var(--line);border-radius:18px;padding:20px}.rows{display:grid;gap:9px}.row{display:grid;grid-template-columns:1fr auto;gap:15px;align-items:center;padding:13px;border:1px solid var(--line);border-radius:12px}.row strong{display:block}.row small{color:var(--muted)}.form-actions{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap}.check{display:flex;gap:8px;align-items:center}.check input{width:auto}.media-note{padding:13px;border-radius:12px;background:var(--soft);color:#5d5046;font-size:12px;margin-bottom:15px}@media(max-width:900px){.grid{grid-template-columns:1fr}.admin-head{align-items:flex-start;flex-direction:column}.wrap{margin-top:18px}.panel{padding:16px}.admin-head h1{font-size:30px}}
</style>
</head>
<body>
<?php if (!$config): ?>
<div class="auth"><h1>Первый запуск</h1><p>Создайте администратора. Пароль хранится только в виде хеша в закрытой папке storage.</p><?php if($error):?><div class="notice error"><?=aesc($error)?></div><?php endif;?><form method="post"><input type="hidden" name="action" value="setup"><label class="field"><span>Логин</span><input name="username" value="admin" required></label><label class="field"><span>Пароль, минимум 10 символов</span><input type="password" name="password" required></label><button class="btn">Создать администратора</button></form></div>
<?php elseif (!$logged): ?>
<div class="auth"><h1>Вход в админку</h1><?php if($message):?><div class="notice"><?=aesc($message)?></div><?php endif;?><?php if($error):?><div class="notice error"><?=aesc($error)?></div><?php endif;?><form method="post"><input type="hidden" name="action" value="login"><label class="field"><span>Логин</span><input name="username" autocomplete="username" required></label><label class="field"><span>Пароль</span><input type="password" name="password" autocomplete="current-password" required></label><button class="btn">Войти</button></form></div>
<?php else: ?>
<div class="wrap">
<header class="admin-head"><div><small>ÉLAN SKIN · управление контентом</small><h1><?=aesc((string)$schemas[$type]['label'])?></h1></div><div class="head-actions"><a class="btn media" href="/admin/media.php">Медиа</a><a class="btn seo" href="/admin/seo.php">SEO</a><a class="btn alt" href="/" target="_blank">Открыть сайт ↗</a><a class="btn alt" href="?logout=1">Выйти</a></div></header>
<nav class="tabs"><?php foreach($schemas as $key=>$schema):?><a class="<?=$key===$type?'active':''?>" href="?type=<?=$key?>"><?=aesc((string)$schema['label'])?></a><?php endforeach;?><a href="/admin/media.php">Медиа</a><a href="/admin/seo.php">SEO страниц</a></nav>
<?php if($message):?><div class="notice"><?=aesc($message)?></div><?php endif;?><?php if($error):?><div class="notice error"><?=aesc($error)?></div><?php endif;?>
<div class="grid">
<section class="panel"><div class="media-note">У изображений есть два варианта: вставить URL/путь или сразу загрузить файл при редактировании записи. Загрузка безопасно принимает только JPG, PNG и WebP.</div><div class="rows"><?php foreach(load_content($type) as $item):?><div class="row"><div><strong><?=aesc((string)($item['title']??'Без названия'))?></strong><small><?=aesc((string)($item['price']??$item['date']??$item['period']??''))?><?=$item['featured']??false?' · на главной':''?></small></div><a class="btn alt" href="?type=<?=aesc($type)?>&edit=<?=rawurlencode((string)$item['id'])?>">Изменить</a></div><?php endforeach;?></div></section>
<aside class="panel"><h2 style="font:26px Georgia,serif;margin-top:0"><?=$edit?'Редактирование':'Новая запись'?></h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=aesc(csrf())?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=aesc((string)($edit['id']??''))?>"><?php if(in_array($type,['services','articles','cases'],true)):?><label class="field"><span>Slug / адрес страницы</span><input name="slug" value="<?=aesc((string)($edit['slug']??''))?>" placeholder="создастся автоматически"></label><?php endif;?><?php foreach($schemas[$type]['fields'] as $field=>$label)field_control($field,(string)$label,$edit??[]);?><label class="check"><input type="checkbox" name="featured" value="1" <?=!empty($edit['featured'])?'checked':''?>> Показывать среди избранного на главной</label><div class="form-actions"><button class="btn" type="submit">Сохранить</button><a class="btn alt" href="?type=<?=aesc($type)?>">Очистить форму</a></div></form><?php if($edit):?><form method="post" onsubmit="return confirm('Удалить запись?')" style="margin-top:10px"><input type="hidden" name="csrf" value="<?=aesc(csrf())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=aesc((string)$edit['id'])?>"><button class="btn danger" type="submit">Удалить</button></form><?php endif;?></aside>
</div></div>
<?php endif; ?>
</body></html>
