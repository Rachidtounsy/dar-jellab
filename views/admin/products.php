<div class="head"><h1>المنتجات <small class="muted">(<?= count($ps) ?>)</small></h1><a class="btn" href="<?= url('admin/products/new') ?>">+ منتج جديد</a></div>
<form class="bar2" method="get"><input name="q" placeholder="بحث..." value="<?= e($_GET['q'] ?? '') ?>"><select name="segment" onchange="this.form.submit()"><option value="">كل الفئات</option><?php foreach (SEG_AR as $k => $l): ?><option value="<?= $k ?>" <?= ($_GET['segment'] ?? '') === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select><button class="btn btn-ghost sm">بحث</button></form>
<div class="tbl-w"><table class="tbl"><tr><th></th><th>المنتج</th><th>الفئة</th><th>الثمن</th><th>المخزون</th><th>الحالة</th></tr>
<?php foreach ($ps as $p): ?><tr><td><?php if ($p['img']): ?><img class="th" src="<?= e(upload_url(thumb_path($p['img']))) ?>" alt=""><?php else: ?><span class="th ph"></span><?php endif; ?></td>
<td><a href="<?= url('admin/products/' . $p['id']) ?>"><b><?= e($p['name_ar']) ?></b></a><br><small class="muted"><?= e($p['name_fr']) ?></small></td>
<td><?= e(SEG_AR[$p['segment']]) ?> · <?= e($p['cat_ar']) ?></td><td><?= e(money($p['price'])) ?></td>
<td class="<?= (int)$p['stock'] === 0 ? 'bad' : '' ?>"><?= (int)$p['stock'] ?></td><td><?= $p['active'] ? '<span class="pill ok">منشور</span>' : '<span class="pill">مخفي</span>' ?><?= $p['featured'] ? ' <span class="pill gold">مميز</span>' : '' ?></td></tr>
<?php endforeach; if (!$ps): ?><tr><td colspan="6" class="center muted">لا توجد منتجات.</td></tr><?php endif; ?></table></div>
