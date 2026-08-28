<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/media-helper.php';

if (empty($_SESSION['admin'])) {
    header('Location: /admin/');
    exit;
}

function mesc(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function media_csrf(): string { if (empty($_SESSION['media_csrf'])) $_SESSION['media_csrf'] = bin2hex(random_bytes(24)); return (string)$_SESSION['media_csrf']; }
function media_csrf_ok(): bool { return isset($_POST['csrf']) && hash_equals((string)($_SESSION['media_csrf'] ?? ''), (string)$_POST['csrf']); }

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!media_csrf_ok()) {
        $error = 'Сессия устарела. Обновите страницу.';
    } else {
        try {
            $path = admin_upload_image('library');
            if ($path === null) $error = 'Выберите изображение.';
            else $message = 'Файл загружен: ' . $path;
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}

$files = [];
$root = admin_upload_root();
if (is_dir($root)) {
    foreach (glob($root . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $file) {
        if (is_file($file)) $files[] = $file;
    }
    usort($files, static fn($a,$b) => filemtime($b) <=> filemtime($a));
}
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Медиа — ÉLAN SKIN</title>
<style>
:root{--bg:#f3efe9;--paper:#fff;--ink:#1d1916;--muted:#746d66;--line:#ddd4ca;--dark:#181411;--accent:#5f7567}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 Arial,sans-serif}.wrap{width:min(1180px,calc(100% - 28px));margin:28px auto}a{text-decoration:none;color:inherit}.head{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:22px}.head h1{margin:0;font:36px Georgia,serif}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:999px;padding:11px 17px;background:var(--dark);color:#fff;cursor:pointer}.btn.alt{background:#e8dfd5;color:#2b241f}.panel{padding:20px;background:#fff;border:1px solid var(--line);border-radius:18px;margin-bottom:20px}.upload{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:end}.field{display:grid;gap:7px}.field span{font-size:11px;font-weight:700;color:var(--muted)}input[type=file]{width:100%;padding:12px;border:1px solid var(--line);border-radius:12px;background:#faf7f3}.notice{padding:12px 14px;border-radius:10px;background:#e5f2e8;margin-bottom:15px}.notice.error{background:#f5dfdd}.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.item{overflow:hidden;background:#fff;border:1px solid var(--line);border-radius:16px}.item img{width:100%;aspect-ratio:4/3;object-fit:cover;background:#eee}.meta{padding:12px;display:grid;gap:9px}.path{font:11px/1.35 monospace;word-break:break-all;color:#5d554f}.copy{border:0;border-radius:999px;padding:9px 12px;background:#eee6dc;cursor:pointer;font-size:12px}.empty{color:var(--muted)}@media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}.upload{grid-template-columns:1fr}.head{align-items:flex-start;flex-direction:column}}@media(max-width:560px){.grid{grid-template-columns:1fr}.head h1{font-size:30px}}
</style>
</head>
<body>
<div class="wrap">
<header class="head"><div><small>ÉLAN SKIN · медиатека</small><h1>Изображения</h1></div><div class="actions"><a class="btn alt" href="/admin/">Контент</a><a class="btn alt" href="/admin/settings.php">Настройки</a><a class="btn alt" href="/admin/seo.php">SEO</a><a class="btn" href="/" target="_blank">Открыть сайт ↗</a></div></header>
<?php if($message):?><div class="notice"><?=mesc($message)?></div><?php endif;?>
<?php if($error):?><div class="notice error"><?=mesc($error)?></div><?php endif;?>
<section class="panel"><form method="post" enctype="multipart/form-data" class="upload"><input type="hidden" name="csrf" value="<?=mesc(media_csrf())?>"><label class="field"><span>JPG, PNG или WebP до 8 МБ</span><input type="file" name="library_file" accept="image/jpeg,image/png,image/webp" required></label><button class="btn" type="submit">Загрузить</button></form></section>
<section class="panel"><h2 style="margin-top:0;font:28px Georgia,serif">Медиатека</h2><?php if(!$files):?><p class="empty">Пока нет загруженных файлов. Изображения, добавленные прямо из форм услуг/статей/кейсов, тоже будут появляться здесь.</p><?php else:?><div class="grid"><?php foreach($files as $file):$name=basename($file);$path=admin_upload_public_prefix().$name;?><article class="item"><img loading="lazy" src="<?=mesc($path)?>" alt=""><div class="meta"><div class="path"><?=mesc($path)?></div><button class="copy" type="button" data-copy="<?=mesc($path)?>">Скопировать путь</button></div></article><?php endforeach;?></div><?php endif;?></section>
</div>
<script>document.addEventListener('click',function(e){var b=e.target.closest('[data-copy]');if(!b)return;navigator.clipboard.writeText(b.dataset.copy).then(function(){var t=b.textContent;b.textContent='Скопировано ✓';setTimeout(function(){b.textContent=t},1300)});});</script>
</body></html>
