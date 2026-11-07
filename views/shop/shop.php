<?php
$segCats = !empty($f['segment']) ? ($tree[$f['segment']] ?? []) : [];
$active = array_intersect_key($f, array_flip(['color', 'fabric', 'stitch', 'occasion', 'min', 'max']));
$sorts = ['new' => 'sort_new', 'popular' => 'sort_popular', 'price_asc' => 'sort_price_asc', 'price_desc' => 'sort_price_desc'];
?>
<section class="wrap pad">
  <nav class="crumbs"><a href="<?= url() ?>"><?= e(t('home')) ?></a> / <a href="<?= url('shop') ?>"><?= e(t('shop')) ?></a><?php if (!empty($f['segment'])): ?> / <a href="<?= url('shop/' . $f['segment']) ?>"><?= e(segment_label($f['segment'])) ?></a><?php endif; ?></nav>
  <div class="sec-h"><h1><?= e($title) ?></h1><span class="muted"><?= (int)$total ?> <?= e(t('items')) ?></span></div>
  <?php if ($segCats): ?><div class="chips"><a class="chip <?= empty($f['cat']) ? 'on' : '' ?>" href="<?= url('shop/' . $f['segment']) ?>"><?= e(t('all')) ?></a>
    <?php foreach ($segCats as $c): ?><a class="chip <?= ($f['cat'] ?? '') === $c['slug'] ? 'on' : '' ?>" href="<?= url('shop/' . $f['segment'] . '/' . $c['slug']) ?>"><?= e(tr($c)) ?></a><?php endforeach; ?></div><?php endif; ?>

  <div class="shop">
    <button class="btn btn-ghost fbtn" data-open="filters"><?= e(t('filters')) ?><?= $active ? ' (' . count($active) . ')' : '' ?></button>
    <aside class="filters" id="filters">
      <div class="filters-h"><b><?= e(t('filters')) ?></b><button class="icon-btn" data-close="filters" aria-label="×"><svg viewBox="0 0 24 24"><path d="M5 5l14 14M19 5 5 19"/></svg></button></div>
      <form method="get" action="<?= url($path) ?>">
        <?php if (!empty($f['q'])): ?><input type="hidden" name="q" value="<?= e($f['q']) ?>"><?php endif; ?>
        <?php if (!empty($f['sort'])): ?><input type="hidden" name="sort" value="<?= e($f['sort']) ?>"><?php endif; ?>
        <details open><summary><?= e(t('f_color')) ?></summary><div class="f-sw">
          <?php foreach (terms_of('color') as $c): ?><label title="<?= e(tr($c)) ?>"><input type="radio" name="color" value="<?= e($c['slug']) ?>" <?= ($f['color'] ?? '') === $c['slug'] ? 'checked' : '' ?>><i style="--c:<?= e($c['hex']) ?>"></i></label><?php endforeach; ?></div></details>
        <?php foreach (['fabric' => 'f_fabric', 'stitch' => 'f_stitch', 'occasion' => 'f_occasion'] as $type => $lbl): ?>
          <details <?= isset($f[$type]) ? 'open' : '' ?>><summary><?= e(t($lbl)) ?></summary><div class="f-list">
            <label><input type="radio" name="<?= $type ?>" value="" <?= empty($f[$type]) ? 'checked' : '' ?>> <?= e(t('all')) ?></label>
            <?php foreach (terms_of($type) as $x): ?><label><input type="radio" name="<?= $type ?>" value="<?= e($x['slug']) ?>" <?= ($f[$type] ?? '') === $x['slug'] ? 'checked' : '' ?>> <?= e(tr($x)) ?></label><?php endforeach; ?></div></details>
        <?php endforeach; ?>
        <details open><summary><?= e(t('f_price')) ?></summary><div class="f-price"><input type="number" name="min" min="0" placeholder="<?= e(t('min')) ?>" value="<?= e($f['min'] ?? '') ?>"><span>—</span><input type="number" name="max" min="0" placeholder="<?= e(t('max')) ?>" value="<?= e($f['max'] ?? '') ?>"></div></details>
        <div class="f-act"><button class="btn"><?= e(t('apply')) ?></button><?php if ($active): ?><a class="btn btn-ghost" href="<?= url($path) ?>"><?= e(t('reset')) ?></a><?php endif; ?></div>
      </form>
    </aside>
    <div class="results">
      <div class="toolbar">
        <div class="tags"><?php foreach ($active as $k => $v): $nm = in_array($k, ['color', 'fabric', 'stitch', 'occasion']) ? tr(term_by($k, $v) ?? ['name_ar' => $v]) : ($k === 'min' ? t('min') . ' ' . $v : t('max') . ' ' . $v); ?>
          <a class="tag" href="<?= ($k === 'occasion' && str_starts_with($path, 'occasion/')) ? shop_url(array_diff_key($f, ['page' => 1, 'segment' => 1, 'cat' => 1, 'accessory' => 1, 'sale' => 1, $k => 1]), 'shop') : shop_url(array_diff_key($f, ['page' => 1, 'segment' => 1, 'cat' => 1, 'accessory' => 1, 'sale' => 1, $k => 1]), $path) ?>"><?= e($nm) ?> ×</a><?php endforeach; ?></div>
        <form method="get" class="sort"><?php foreach ($f as $k => $v) if ($k !== 'sort' && $k !== 'page' && $k !== 'segment' && $k !== 'cat' && $k !== 'accessory' && $k !== 'sale' && $k !== '_path') echo '<input type="hidden" name="' . e($k) . '" value="' . e($v) . '">'; ?>
          <select name="sort" onchange="this.form.submit()" aria-label="<?= e(t('sort')) ?>"><?php foreach ($sorts as $k => $l): ?><option value="<?= $k ?>" <?= ($f['sort'] ?? 'new') === $k ? 'selected' : '' ?>><?= e(t($l)) ?></option><?php endforeach; ?></select></form>
      </div>
      <?php if (!$products): ?><div class="empty"><p><?= e(t('no_results')) ?></p><a class="btn" href="<?= url('shop') ?>"><?= e(t('view_all')) ?></a></div>
      <?php else: ?><div class="grid g3"><?php foreach ($products as $p) partial('card', ['p' => $p]); ?></div><?php endif; ?>
      <?php if ($pages > 1): ?><nav class="pager"><?php for ($i = 1; $i <= $pages; $i++): ?><a class="<?= $i === $page ? 'on' : '' ?>" href="<?= shop_url(array_diff_key($f, ['segment' => 1, 'cat' => 1, 'accessory' => 1, 'sale' => 1, 'page' => 1]) + ['page' => $i], $path) ?>"><?= $i ?></a><?php endfor; ?></nav><?php endif; ?>
    </div>
  </div>
</section>
