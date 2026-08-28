<?php

declare(strict_types=1);
session_start();
require __DIR__ . '/../includes/content.php';
require __DIR__ . '/../includes/site-settings.php';

if (empty($_SESSION['admin'])) { header('Location: /admin/'); exit; }
function se(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
if (empty($_SESSION['seo_csrf'])) $_SESSION['seo_csrf']=bin2hex(random_bytes(24));
$csrf=(string)$_SESSION['seo_csrf'];

$labels=['home'=>'Главная','services'=>'Услуги','price'=>'Цены','cases'=>'Результаты','blog'=>'Блог','videos'=>'Видео'];
$all=seo_load_all();
$page=(string)($_GET['page']??'home');if(!isset($labels[$page]))$page='home';
$message='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!hash_equals($csrf,(string)($_POST['csrf']??''))){$error='Сессия формы устарела. Обновите страницу.';}
  else{
    $page=(string)($_POST['page']??'home');if(!isset($labels[$page]))$page='home';
    $row=[];foreach(['title','description','h1','canonical','robots','og_title','og_description','og_image'] as $field)$row[$field]=trim((string)($_POST[$field]??''));
    $all[$page]=array_merge($all[$page]??[],$row);
    if(seo_save_all($all)){$message='SEO-настройки сохранены.';}else{$error='Не удалось сохранить SEO-настройки.';}
  }
}
$current=$all[$page]??[];
?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SEO — управление сайтом</title><style>
:root{--bg:#f3efe9;--paper:#fff;--ink:#1d1916;--muted:#746d66;--line:#ddd4ca;--dark:#181411;--accent:#9b775d}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 Arial,sans-serif}a{color:inherit;text-decoration:none}.wrap{width:min(1180px,calc(100% - 28px));margin:30px auto}.head{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:20px}.head h1{margin:0;font:34px Georgia,serif}.btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:999px;padding:11px 17px;background:var(--dark);color:#fff;cursor:pointer}.btn.alt{background:#e9e1d8;color:#2c251f}.tabs{display:flex;gap:7px;flex-wrap:wrap;margin:0 0 22px}.tabs a{padding:9px 13px;border:1px solid var(--line);border-radius:999px}.tabs a.active{background:var(--dark);color:#fff}.panel{background:#fff;border:1px solid var(--line);border-radius:18px;padding:22px}.grid{display:grid;grid-template-columns:1.15fr .85fr;gap:22px}.field{display:grid;gap:6px;margin:14px 0}.field span{font-size:11px;color:var(--muted)}input,textarea,select{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:10px;background:#fff}textarea{min-height:105px;resize:vertical}.notice{padding:12px 14px;border-radius:10px;margin:12px 0;background:#e6f2e8}.error{background:#f5dfdd}.preview{position:sticky;top:20px}.serp{padding:18px;border:1px solid var(--line);border-radius:14px}.serp-title{color:#1a0dab;font-size:20px;line-height:1.3}.serp-url{color:#188038;font-size:13px;margin:6px 0}.serp-desc{color:#4d5156;line-height:1.5}.hint{color:var(--muted);font-size:12px}.counter{font-size:11px;color:var(--muted);text-align:right}@media(max-width:850px){.grid{grid-template-columns:1fr}.head{align-items:flex-start;flex-direction:column}.preview{position:static}}
</style></head><body><div class="wrap"><header class="head"><div><small>ÉLAN SKIN · управление сайтом</small><h1>SEO</h1></div><div><a class="btn alt" href="/admin/">← Контент</a> <a class="btn alt" href="/" target="_blank">Открыть сайт ↗</a></div></header>
<nav class="tabs"><?php foreach($labels as $key=>$label):?><a class="<?=$key===$page?'active':''?>" href="?page=<?=$key?>"><?=se($label)?></a><?php endforeach;?></nav>
<?php if($message):?><div class="notice"><?=se($message)?></div><?php endif;?><?php if($error):?><div class="notice error"><?=se($error)?></div><?php endif;?>
<div class="grid"><section class="panel"><form method="post"><input type="hidden" name="csrf" value="<?=se($csrf)?>"><input type="hidden" name="page" value="<?=se($page)?>">
<label class="field"><span>SEO title</span><input id="seoTitle" name="title" maxlength="180" value="<?=se((string)($current['title']??''))?>"><div class="counter"><span id="titleCount">0</span> символов</div></label>
<label class="field"><span>Meta description</span><textarea id="seoDescription" name="description" maxlength="400"><?=se((string)($current['description']??''))?></textarea><div class="counter"><span id="descCount">0</span> символов</div></label>
<label class="field"><span>H1 (резерв под индивидуальный заголовок страницы)</span><input name="h1" value="<?=se((string)($current['h1']??''))?>"></label>
<label class="field"><span>Canonical URL</span><input name="canonical" type="url" placeholder="https://example.ru/page" value="<?=se((string)($current['canonical']??''))?>"></label>
<label class="field"><span>Robots</span><select name="robots"><?php foreach(['noindex,nofollow'=>'noindex,nofollow — тест','index,follow'=>'index,follow — рабочий сайт','noindex,follow'=>'noindex,follow'] as $value=>$label):?><option value="<?=se($value)?>" <?=($current['robots']??'')===$value?'selected':''?>><?=se($label)?></option><?php endforeach;?></select></label>
<label class="field"><span>Open Graph title</span><input name="og_title" value="<?=se((string)($current['og_title']??''))?>" placeholder="Если пусто — берётся SEO title"></label>
<label class="field"><span>Open Graph description</span><textarea name="og_description" placeholder="Если пусто — берётся Meta description"><?=se((string)($current['og_description']??''))?></textarea></label>
<label class="field"><span>Open Graph image — URL</span><input name="og_image" value="<?=se((string)($current['og_image']??''))?>" placeholder="https://..."></label>
<button class="btn" type="submit">Сохранить SEO</button></form></section>
<aside class="panel preview"><h2 style="font:25px Georgia,serif;margin-top:0">Предпросмотр выдачи</h2><div class="serp"><div class="serp-title" id="previewTitle"></div><div class="serp-url">cosmetologist-dev.denisnazarov.online</div><div class="serp-desc" id="previewDesc"></div></div><p class="hint">Рекомендуемый ориентир: title около 50–65 символов, description около 120–160. Это не жёсткий лимит поисковых систем.</p><p class="hint"><strong>Важно:</strong> TEST оставляем noindex. На рабочем домене переключаем нужные страницы на index,follow только после финальной проверки.</p></aside></div></div><script>
(function(){var t=document.getElementById('seoTitle'),d=document.getElementById('seoDescription'),pt=document.getElementById('previewTitle'),pd=document.getElementById('previewDesc'),tc=document.getElementById('titleCount'),dc=document.getElementById('descCount');function sync(){pt.textContent=t.value||'Заголовок страницы';pd.textContent=d.value||'Описание страницы для поисковой выдачи.';tc.textContent=t.value.length;dc.textContent=d.value.length;}t.addEventListener('input',sync);d.addEventListener('input',sync);sync();})();
</script></body></html>
