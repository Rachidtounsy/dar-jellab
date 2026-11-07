<h1>مرحبا 👋</h1>
<div class="stats"><div><b><?= (int)$n['products'] ?></b><span>منتج</span></div><div><b><?= (int)$n['active'] ?></b><span>منشور</span></div><div class="<?= $n['out'] ? 'warn' : '' ?>"><b><?= (int)$n['out'] ?></b><span>نفد مخزونه</span></div></div>
<?php if (!$wa): ?><div class="flash err">لم تضف رقم الواتساب بعد، <a href="<?= url('admin/settings') ?>">أضفه من الإعدادات</a> باش يخدم زر الطلب.</div><?php endif; ?>
<div class="cols"><div class="box"><h2>إجراءات سريعة</h2><a class="btn" href="<?= url('admin/products/new') ?>">+ منتج جديد</a> <a class="btn btn-ghost" href="<?= url('admin/settings') ?>">الإعدادات</a></div>
<div class="box"><h2>الأكثر مشاهدة</h2><?php foreach ($top as $t): ?><p><a href="<?= url('admin/products/' . $t['id']) ?>"><?= e($t['name_ar']) ?></a> <span class="muted">— <?= (int)$t['views'] ?></span></p><?php endforeach; ?></div></div>
