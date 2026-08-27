<?php

declare(strict_types=1);
$site=require __DIR__.'/config.php';require __DIR__.'/includes/content.php';require __DIR__.'/includes/layout.php';$articles=load_content('articles');usort($articles,fn($a,$b)=>strcmp((string)$b['date'],(string)$a['date']));
render_header($site,'blog','Блог','Статьи о домашнем уходе, процедурах и здоровье кожи.');render_page_hero('Блог','Знания, которые помогают<br><em>лучше понимать свою кожу.</em>','Статьи отвечают на реальные вопросы клиентов, усиливают экспертность специалиста и формируют отдельный SEO-контур сайта.');
?>
<main><section class="section"><div class="container"><div class="article-index"><?php foreach($articles as $article):?><article class="article-tile reveal"><span class="article-meta"><?=esc(format_date_ru((string)$article['date']))?> · <?=esc((string)$article['category'])?> · <?=esc((string)$article['read_time'])?></span><h2><?=esc((string)$article['title'])?></h2><p><?=esc((string)$article['excerpt'])?></p><a class="text-link" href="/article.php?slug=<?=rawurlencode((string)$article['slug'])?>">Читать статью <span>↗</span></a></article><?php endforeach;?></div></div></section></main><?php render_footer($site);?>