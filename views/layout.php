<?php
$tree = nav_tree(); $name = setting_l('site_name'); $desc = $meta_desc ?? setting_l('tagline');
$social = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'telegram' => 'Telegram', 'snapchat' => 'Snapchat', 'pinterest' => 'Pinterest', 'x' => 'X'];
$occ = terms_of('occasion');
?><!doctype html>
<html lang="<?= lang() ?>" dir="<?= dir_() ?>">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? $name) ?><?= isset($title) && $title !== $name ? ' | ' . e($name) : '' ?></title>
<meta name="description" content="<?= e($desc) ?>"><meta name="theme-color" content="#1F3A5F">
<link rel="icon" href="<?= asset('icon.svg') ?>" type="image/svg+xml">
<link rel="preload" href="<?= asset('fonts/tajawal-arabic-500-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<?php if ($px = setting('meta_pixel')): ?><script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','<?= e($px) ?>');fbq('track','PageView');</script><?php endif; ?>
</head>
<body>
<?php if ($a = setting_l('announce')): ?><div class="announce"><?= e($a) ?></div><?php endif; ?>
<header class="hdr">
  <div class="wrap hdr-in">
    <button class="icon-btn burger" data-open="drawer" aria-label="<?= e(t('menu')) ?>"><svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg></button>
    <?php partial('logo'); ?>
    <nav class="nav" aria-label="main">
      <?php foreach (SEGMENTS as $s): if (empty($tree[$s])) continue; ?>
        <div class="nav-i"><a href="<?= url('shop/' . $s) ?>"><?= e(segment_label($s)) ?></a>
          <div class="mega"><div class="mega-in">
            <?php foreach ($tree[$s] as $c): ?><a href="<?= url('shop/' . $s . '/' . $c['slug']) ?>"><?= e(tr($c)) ?></a><?php endforeach; ?>
            <a class="all" href="<?= url('shop/' . $s) ?>"><?= e(t('view_all')) ?> ←</a>
          </div></div></div>
      <?php endforeach; ?>
      <div class="nav-i"><a href="<?= url('accessories') ?>"><?= e(t('accessories')) ?></a></div>
      <div class="nav-i"><a href="#"><?= e(t('occasions')) ?></a>
        <div class="mega"><div class="mega-in"><?php foreach ($occ as $o): ?><a href="<?= url('occasion/' . $o['slug']) ?>"><?= e(tr($o)) ?></a><?php endforeach; ?></div></div></div>
      <div class="nav-i"><a href="<?= url('sale') ?>" class="hot"><?= e(t('sale')) ?></a></div>
    </nav>
    <form class="search" action="<?= url('search') ?>" role="search"><input name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="<?= e(t('search_ph')) ?>" aria-label="<?= e(t('search')) ?>"><button aria-label="<?= e(t('search')) ?>"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button></form>
    <div class="hdr-act">
      <a class="lang" href="<?= url('lang/' . (lang() === 'ar' ? 'fr' : 'ar')) ?>"><?= lang() === 'ar' ? 'FR' : 'عربية' ?></a>
      <?php if (wa_number()): ?><a class="btn btn-wa sm" href="<?= e(wa_link(t('wa_hello'))) ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg><span><?= e(t('whatsapp')) ?></span></a><?php endif; ?>
    </div>
  </div>
</header>

<div class="drawer" id="drawer" hidden>
  <div class="drawer-bg" data-close="drawer"></div>
  <aside class="drawer-p">
    <div class="drawer-h"><?php partial('logo'); ?><button class="icon-btn" data-close="drawer" aria-label="×"><svg viewBox="0 0 24 24"><path d="M5 5l14 14M19 5 5 19"/></svg></button></div>
    <?php foreach (SEGMENTS as $s): if (empty($tree[$s])) continue; ?>
      <details><summary><?= e(segment_label($s)) ?></summary>
        <?php foreach ($tree[$s] as $c): ?><a href="<?= url('shop/' . $s . '/' . $c['slug']) ?>"><?= e(tr($c)) ?></a><?php endforeach; ?>
        <a class="all" href="<?= url('shop/' . $s) ?>"><?= e(t('view_all')) ?></a></details>
    <?php endforeach; ?>
    <details><summary><?= e(t('occasions')) ?></summary><?php foreach ($occ as $o): ?><a href="<?= url('occasion/' . $o['slug']) ?>"><?= e(tr($o)) ?></a><?php endforeach; ?></details>
    <a class="d-link" href="<?= url('accessories') ?>"><?= e(t('accessories')) ?></a>
    <a class="d-link hot" href="<?= url('sale') ?>"><?= e(t('sale')) ?></a>
    <a class="d-link" href="<?= url('about') ?>"><?= e(t('page_about')) ?></a>
    <a class="d-link" href="<?= url('contact') ?>"><?= e(t('page_contact')) ?></a>
    <a class="d-link" href="<?= url('lang/' . (lang() === 'ar' ? 'fr' : 'ar')) ?>"><?= lang() === 'ar' ? 'Français' : 'العربية' ?></a>
  </aside>
</div>

<main id="main"><?= $content ?></main>

<footer class="ftr">
  <div class="wrap ftr-g">
    <div><?php partial('logo'); ?><p class="muted"><?= e(setting_l('about')) ?></p>
      <div class="social"><?php foreach ($social as $k => $lbl) if ($u = setting($k)): ?><a href="<?= e($u) ?>" target="_blank" rel="noopener" aria-label="<?= $lbl ?>"><?= $lbl ?></a><?php endif; ?></div></div>
    <div><h4><?= e(t('shop')) ?></h4><?php foreach (SEGMENTS as $s): ?><a href="<?= url('shop/' . $s) ?>"><?= e(segment_label($s)) ?></a><?php endforeach; ?><a href="<?= url('accessories') ?>"><?= e(t('accessories')) ?></a><a href="<?= url('sale') ?>"><?= e(t('sale')) ?></a></div>
    <div><h4><?= e(t('info')) ?></h4><a href="<?= url('about') ?>"><?= e(t('page_about')) ?></a><a href="<?= url('delivery') ?>"><?= e(t('page_delivery')) ?></a><a href="<?= url('contact') ?>"><?= e(t('page_contact')) ?></a></div>
    <div><h4><?= e(t('page_contact')) ?></h4>
      <?php foreach (['whatsapp1', 'whatsapp2'] as $k) if ($n = setting($k)): ?><a href="<?= e(wa_link('', preg_replace('/\D+/', '', $n))) ?>" target="_blank" rel="noopener" dir="ltr">WhatsApp: <?= e($n) ?></a><?php endif; ?>
      <?php if ($x = setting('phone')): ?><a href="tel:<?= e(preg_replace('/[^\d+]/', '', $x)) ?>" dir="ltr"><?= e($x) ?></a><?php endif; ?>
      <?php if ($x = setting('email')): ?><a href="mailto:<?= e($x) ?>"><?= e($x) ?></a><?php endif; ?>
      <?php if ($x = setting_l('address')): ?><p class="muted"><?= nl2br(e($x)) ?></p><?php endif; ?>
      <?php if ($x = setting_l('hours')): ?><p class="muted"><?= e($x) ?></p><?php endif; ?></div>
  </div>
  <div class="wrap ftr-b"><span>© <?= date('Y') ?> <?= e($name) ?></span><span class="muted"><?= e(t('cod_note')) ?></span></div>
</footer>

<nav class="bar" aria-label="mobile">
  <a href="<?= url() ?>"><svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg><?= e(t('home')) ?></a>
  <button data-open="drawer"><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h10"/></svg><?= e(t('categories')) ?></button>
  <a href="<?= url('search') ?>"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><?= e(t('search')) ?></a>
  <?php if (wa_number()): ?><a class="wa" href="<?= e(wa_link(t('wa_hello'))) ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg><?= e(t('whatsapp')) ?></a><?php endif; ?>
</nav>
<script src="<?= asset('js/app.js') ?>" defer></script>
</body></html>
