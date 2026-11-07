<h1>الألوان والخيارات</h1><div class="chips"><?php foreach (TERM_TYPES as $k => $l): ?><a class="chip <?= $k === $type ? 'on' : '' ?>" href="<?= url('admin/terms?type=' . $k) ?>"><?= $l ?></a><?php endforeach; ?></div>
<form method="post" class="box add"><?= csrf_field() ?><input type="hidden" name="type" value="<?= $type ?>"><input type="hidden" name="action" value="add"><b>إضافة:</b>
<input name="name_ar" placeholder="الاسم بالعربية" required><input name="name_fr" placeholder="Nom en français" dir="ltr"><?php if ($type === 'color'): ?><input type="color" name="hex" value="#999999" title="اللون"><?php endif; ?><button class="btn sm">إضافة</button></form>
<form method="post"><?= csrf_field() ?><input type="hidden" name="type" value="<?= $type ?>"><input type="hidden" name="action" value="save">
<div class="tbl-w"><table class="tbl"><tr><th>عربية</th><th>Français</th><?php if ($type === 'color'): ?><th>اللون</th><?php endif; ?><th>الترتيب</th><th>مفعل</th><th>مستعمل</th><th></th></tr>
<?php foreach ($items as $t): ?><tr><td><input name="t[<?= $t['id'] ?>][name_ar]" value="<?= e($t['name_ar']) ?>"></td><td><input name="t[<?= $t['id'] ?>][name_fr]" value="<?= e($t['name_fr']) ?>" dir="ltr"></td>
<?php if ($type === 'color'): ?><td><input type="color" name="t[<?= $t['id'] ?>][hex]" value="<?= e($t['hex']) ?>"></td><?php endif; ?>
<td><input type="number" class="n" name="t[<?= $t['id'] ?>][sort]" value="<?= (int)$t['sort'] ?>"></td><td><input type="checkbox" name="t[<?= $t['id'] ?>][active]" <?= $t['active'] ? 'checked' : '' ?>></td><td><?= (int)$t['used'] ?></td>
<td><button class="x" name="action" value="delete" formaction="" onclick="this.form.querySelector('[name=id]')||this.form.insertAdjacentHTML('beforeend','<input type=hidden name=id>');this.form.querySelector('[name=id]').value=<?= $t['id'] ?>;return confirm('حذف؟')">🗑</button></td></tr><?php endforeach; ?></table></div>
<button class="btn">حفظ التعديلات</button></form>
