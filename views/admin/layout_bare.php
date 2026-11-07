<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>لوحة التحكم</title><link rel="icon" href="<?= asset('icon.svg') ?>"><link rel="stylesheet" href="<?= asset('css/app.css') ?>"><link rel="stylesheet" href="<?= asset('css/admin.css') ?>"></head>
<body class="bare"><?php if ($f = flash()): ?><div class="flash <?= e($f[1]) ?>"><?= e($f[0]) ?></div><?php endif; ?><?= $content ?></body></html>
