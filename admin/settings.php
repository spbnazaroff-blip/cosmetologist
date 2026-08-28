<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/../includes/profile.php';

if (empty($_SESSION['admin'])) {
    header('Location: /admin/');
    exit;
}

function sesc(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function settings_csrf(): string { if (empty($_SESSION['settings_csrf'])) $_SESSION['settings_csrf'] = bin2hex(random_bytes(24)); return (string)$_SESSION['settings_csrf']; }
function settings_csrf_ok(): bool { return isset($_POST['csrf']) && hash_equals((string)($_SESSION['settings_csrf'] ?? ''), (string)$_POST['csrf']); }

$defaults = require __DIR__ . '/../config.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!settings_csrf_ok()) {
        $error = 'Сессия формы устарела. Обновите страницу.';
    } else {
        $data = [];
        foreach (profile_allowed_fields() as $field) $data[$field] = trim((string)($_POST[$field] ?? ''));
        if ($data['site_name'] === '') $error = 'Название сайта не может быть пустым.';
        elseif (site_profile_save($data)) {
            $message = 'Настройки сохранены.';
            $defaults = array_merge($defaults, $data);
        } else $error = 'Не удалось сохранить profile.json.';
    }
}

$fields = [
    'site_name'=>'Название бренда / сайта',
    'eyebrow'=>'Надпись над главным заголовком',
    'headline'=>'Главный заголовок',
    'description'=>'Короткое описание',
    'specialist'=>'Имя / позиционирование специалиста',
    'city'=>'Город',
    'phone'=>'Телефон',
    'telegram'=>'Telegram — ссылка или @username',
    'whatsapp'=>'WhatsApp — ссылка или номер',
    'address'=>'Адрес',
    'booking_url'=>'Ссылка онлайн-записи',
];
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Настройки — ÉLAN SKIN</title>
<style>
:root{--bg:#f3efe9;--paper:#fff;--ink:#1d1916;--muted:#746d66;--line:#ddd4ca;--dark:#181411;--accent:#9b775d}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 Arial,sans-serif}.wrap{width:min(980px,calc(100% - 28px));margin:28px auto}a{text-decoration:none;color:inherit}.head{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:22px}.head h1{margin:0;font:36px Georgia,serif}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:999px;padding:11px 17px;background:var(--dark);color:#fff;cursor:pointer}.btn.alt{background:#e8dfd5;color:#2b241f}.panel{padding:22px;background:#fff;border:1px solid var(--line);border-radius:18px}.field{display:grid;gap:7px;margin:14px 0}.field span{font-size:11px;font-weight:700;color:var(--muted)}input,textarea{width:100%;padding:12px;border:1px solid var(--line);border-radius:11px;background:#fff;font:inherit}textarea{min-height:110px;resize:vertical}.notice{padding:12px 14px;border-radius:10px;background:#e5f2e8;margin-bottom:15px}.notice.error{background:#f5dfdd}.hint{padding:13px 14px;border-radius:12px;background:#f7f1eb;color:#645950;margin-bottom:16px}@media(max-width:700px){.head{align-items:flex-start;flex-direction:column}.head h1{font-size:30px}.panel{padding:16px}}
</style>
</head>
<body>
<div class="wrap">
<header class="head"><div><small>ÉLAN SKIN · общие данные</small><h1>Настройки сайта</h1></div><div class="actions"><a class="btn alt" href="/admin/">Контент</a><a class="btn alt" href="/admin/media.php">Медиа</a><a class="btn alt" href="/admin/seo.php">SEO</a><a class="btn" href="/" target="_blank">Открыть сайт ↗</a></div></header>
<?php if($message):?><div class="notice"><?=sesc($message)?></div><?php endif;?>
<?php if($error):?><div class="notice error"><?=sesc($error)?></div><?php endif;?>
<section class="panel"><div class="hint">Эти поля заменяют демонстрационные данные без правки PHP. После сохранения изменения сразу используются на публичных страницах.</div><form method="post"><input type="hidden" name="csrf" value="<?=sesc(settings_csrf())?>"><?php foreach($fields as $field=>$label):$value=(string)($defaults[$field]??'');?><label class="field"><span><?=sesc($label)?></span><?php if(in_array($field,['description','headline'],true)):?><textarea name="<?=sesc($field)?>"><?=sesc($value)?></textarea><?php else:?><input name="<?=sesc($field)?>" value="<?=sesc($value)?>"><?php endif;?></label><?php endforeach;?><button class="btn" type="submit">Сохранить настройки</button></form></section>
</div>
</body></html>
