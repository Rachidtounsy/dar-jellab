<?php $nav = ['admin' => ['الرئيسية', 'M3 11l9-8 9 8v9H3z'], 'admin/products' => ['المنتجات', 'M4 7h16l-1 13H5zM9 7a3 3 0 0 1 6 0'], 'admin/categories' => ['الأصناف', 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
 'admin/terms' => ['الألوان والخيارات', 'M12 3a9 9 0 1 0 0 18c1.5 0 2-1 1.5-2s0-2 1.5-2h2a3 3 0 0 0 3-3c0-5-4-11-8-11z'], 'admin/settings' => ['الإعدادات', 'M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM3 12h2M19 12h2M12 3v2M12 19v2'], 'admin/password' => ['كلمة المرور', 'M6 11V8a6 6 0 0 1 12 0v3M5 11h14v10H5z']];
$cur = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); ?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title><?= e($title ?? 'لوحة التحكم') ?> | <?= e(setting_l('site_name')) ?></title><link rel="icon" href="<?= asset('icon.svg') ?>">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>"><link rel="stylesheet" href="<?= asset('css/admin.css') ?>"></head>
<body class="adm"><aside class="side"><a class="side-logo" href="<?= url('admin') ?>"><?php partial('mark'); ?><b><?= e(setting_l('site_name')) ?></b></a>
<nav><?php foreach ($nav as $u => [$l, $ic]): $on = $u === 'admin' ? $cur === 'admin' : str_starts_with($cur, $u); ?><a class="<?= $on ? 'on' : '' ?>" href="<?= url($u) ?>"><svg viewBox="0 0 24 24"><path d="<?= $ic ?>"/></svg><?= $l ?></a><?php endforeach; ?>
<a href="<?= url() ?>" target="_blank"><svg viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9M10 6H5v13h13v-5"/></svg>عرض المتجر</a><a href="<?= url('admin/logout') ?>"><svg viewBox="0 0 24 24"><path d="M9 4H5v16h4M16 8l4 4-4 4M20 12H9"/></svg>خروج</a></nav></aside>
<main class="adm-main"><?php if ($f = flash()): ?><div class="flash <?= e($f[1]) ?>"><?= e($f[0]) ?></div><?php endif; ?><?= $content ?></main>
<script src="<?= asset('js/admin.js') ?>" defer></script></body></html>
