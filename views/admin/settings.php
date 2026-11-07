<h1>الإعدادات</h1><p class="muted">كلشي كيتبدل من هنا وكيبان فالموقع مباشرة: الأرقام، العناوين، وروابط الصفحات.</p>
<form method="post" enctype="multipart/form-data" class="form"><?= csrf_field() ?>
<?php foreach ($schema as $sec => $fields): ?><fieldset class="box"><legend><?= e($sec) ?></legend><div class="fg">
<?php foreach ($fields as $f): [$k, $l, $type] = $f + [2 => 'text']; $v = setting($k); ?>
<label class="<?= $type === 'textarea' ? 'full' : '' ?>"><?= e($l) ?>
<?php if ($type === 'textarea'): ?><textarea name="<?= $k ?>" rows="3"><?= e($v) ?></textarea>
<?php elseif ($type === 'file'): ?><?php if ($v): ?><img class="prev" src="<?= e(upload_url($v)) ?>" alt=""><span class="chk"><input type="checkbox" name="rm_<?= $k ?>" value="1"> حذف الصورة</span><?php endif; ?><input type="file" name="<?= $k ?>" accept="image/*">
<?php else: ?><input type="<?= $type === 'url' ? 'text' : $type ?>" <?= $type === 'url' ? 'inputmode="url" placeholder="https://"' : '' ?> name="<?= $k ?>" value="<?= e($v) ?>" <?= in_array($type, ['tel', 'url', 'email']) ? 'dir="ltr"' : '' ?>><?php endif; ?></label>
<?php endforeach; ?></div></fieldset><?php endforeach; ?>
<button class="btn">حفظ الإعدادات</button></form>
