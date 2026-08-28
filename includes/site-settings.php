<?php

declare(strict_types=1);

function seo_storage_file(): string
{
    return defined('CONTENT_STORAGE') ? CONTENT_STORAGE . '/seo.json' : dirname(__DIR__) . '/storage/seo.json';
}

function seo_defaults(): array
{
    return [
        'home'=>['title'=>'ÉLAN SKIN — премиальная эстетическая косметология','description'=>'Персональные программы ухода за кожей, эстетическая косметология и понятный маршрут к ухоженному виду кожи.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
        'services'=>['title'=>'Услуги косметолога — ÉLAN SKIN','description'=>'Профессиональные уходовые процедуры, консультации и персональные программы.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
        'price'=>['title'=>'Цены на услуги косметолога — ÉLAN SKIN','description'=>'Понятный прайс на консультации, уходовые процедуры и персональные программы.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
        'cases'=>['title'=>'Результаты и кейсы — ÉLAN SKIN','description'=>'Кейсы косметолога с исходным запросом, протоколом, сроком и результатом.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
        'blog'=>['title'=>'Блог косметолога — ÉLAN SKIN','description'=>'Статьи о домашнем уходе, процедурах и грамотном отношении к коже.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
        'videos'=>['title'=>'Видео косметолога — ÉLAN SKIN','description'=>'Экспертные видео о домашнем уходе, процедурах и частых вопросах клиентов.','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''],
    ];
}

function seo_load_all(): array
{
    $defaults = seo_defaults();
    $file = seo_storage_file();
    if (!is_file($file)) return $defaults;
    $raw = file_get_contents($file);
    if ($raw === false) return $defaults;
    $data = json_decode($raw, true);
    if (!is_array($data)) return $defaults;
    foreach ($defaults as $key=>$row) $defaults[$key] = array_merge($row, is_array($data[$key] ?? null) ? $data[$key] : []);
    return $defaults;
}

function seo_save_all(array $data): bool
{
    $file = seo_storage_file();
    $dir = dirname($file);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) return false;
    $json = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    return $json !== false && file_put_contents($file, $json, LOCK_EX) !== false;
}

function seo_page(string $key, array $fallback, array $override=[]): array
{
    $all = seo_load_all();
    $saved = is_array($all[$key] ?? null) ? $all[$key] : [];
    $result = array_merge([
        'title'=>'','description'=>'','h1'=>'','canonical'=>'','robots'=>'noindex,nofollow','og_title'=>'','og_description'=>'','og_image'=>''
    ], $fallback, $saved, array_filter($override, static fn($v)=>$v!=='' && $v!==null));
    if ($result['og_title']==='') $result['og_title']=$result['title'];
    if ($result['og_description']==='') $result['og_description']=$result['description'];
    return $result;
}
