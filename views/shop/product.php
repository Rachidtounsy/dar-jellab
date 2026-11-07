<?php $sale = $p['old_price'] && $p['old_price'] > $p['price']; $gal = $images ? array_map(fn($i) => ['full' => upload_url($i['path']), 'th' => upload_url(thumb_path($i['path']))], $images) : [['full' => $ph, 'th' => $ph]];
$desc = tr($p, 'desc'); $abs = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . url('p/' . $p['slug']); ?>
<section class="wrap pad">
  <nav class="crumbs"><a href="<?= url() ?>"><?= e(t('home')) ?></a> / <a href="<?= url('shop/' . $p['segment']) ?>"><?= e(segment_label($p['segment'])) ?></a> / <a href="<?= url('shop/' . $p['segment'] . '/' . $p['cat_slug']) ?>"><?= e(tr($p, 'cat')) ?></a></nav>
  <div class="pdp">
    <div class="gal">
      <div class="gal-main"><img id="gmain" src="<?= e($gal[0]['full']) ?>" alt="<?= e(tr($p)) ?>" width="600" height="800"></div>
      <?php if (count($gal) > 1): ?><div class="gal-th"><?php foreach ($gal as $i => $g): ?><button type="button" data-img="<?= e($g['full']) ?>" class="<?= $i ? '' : 'on' ?>"><img src="<?= e($g['th']) ?>" alt="" loading="lazy"></button><?php endforeach; ?></div><?php endif; ?>
    </div>
    <div class="info" id="pdp" data-name="<?= e(tr($p)) ?>" data-price="<?= e(money($p['price'])) ?>" data-url="<?= e($abs) ?>" data-wa="<?= e(wa_number()) ?>"
         data-l-color="<?= e(t('color')) ?>" data-l-size="<?= e(t('size')) ?>" data-l-price="<?= e(t('price')) ?>" data-l-hello="<?= e(t('wa_order_hello')) ?>" data-l-pick="<?= e(t('pick_options')) ?>" data-l-mtm="<?= e(t('wa_mtm')) ?>">
      <small class="muted"><?= e(segment_label($p['segment'])) ?> · <?= e(tr($p, 'cat')) ?></small>
      <h1><?= e(tr($p)) ?></h1>
      <div class="price lg"><b><?= e(money($p['price'])) ?></b><?php if ($sale): ?><s><?= e(money($p['old_price'])) ?></s><span class="badge badge-sale">-<?= (int)round(100 - $p['price'] / $p['old_price'] * 100) ?>%</span><?php endif; ?></div>

      <?php if ($colors): ?>
      <div class="opt"><b><?= e(t('color')) ?>: <span id="cname"></span></b>
        <div class="f-sw lg"><?php foreach ($colors as $i => $c): ?><label title="<?= e($c['name']) ?>"><input type="radio" name="col" value="<?= $i ?>" <?= $i ? '' : 'checked' ?>><i style="--c:<?= e($c['hex']) ?>"></i></label><?php endforeach; ?></div></div>
      <div class="opt"><b><?= e(t('size')) ?></b> <a href="#sizes" class="muted sm"><?= e(t('size_guide')) ?></a><div class="sizes" id="sizes"></div></div>
      <?php endif; ?>
      <script type="application/json" id="cdata"><?= json_encode($colors, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>

      <?php if ($p['made_to_measure']): ?><label class="mtm"><input type="checkbox" id="mtm"> <span><b><?= e(t('made_to_measure')) ?></b> — <?= e(t('mtm_note')) ?></span></label><?php endif; ?>

      <a id="order" class="btn btn-wa lg" href="#" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/></svg><?= e(t('order_wa')) ?></a>
      <p class="muted sm"><?= e(t('cod_note')) ?></p>
      <ul class="perks"><li><?= e(t('v1_t')) ?></li><li><?= e(t('v2_t')) ?></li><li><?= e(t('v3_t')) ?></li></ul>

      <?php if ($desc): ?><div class="desc"><h3><?= e(t('description')) ?></h3><p><?= nl2br(e($desc)) ?></p></div><?php endif; ?>
      <table class="spec"><?php if ($fabric): ?><tr><th><?= e(t('f_fabric')) ?></th><td><?= e(tr($fabric)) ?></td></tr><?php endif; ?>
        <?php if ($stitch): ?><tr><th><?= e(t('f_stitch')) ?></th><td><?= e(tr($stitch)) ?></td></tr><?php endif; ?>
        <?php $oc = array_filter($terms, fn($x) => $x['type'] === 'occasion'); if ($oc): ?><tr><th><?= e(t('f_occasion')) ?></th><td><?php foreach ($oc as $o): ?><a class="chip sm" href="<?= url('occasion/' . $o['slug']) ?>"><?= e(tr($o)) ?></a> <?php endforeach; ?></td></tr><?php endif; ?>
        <tr><th><?= e(t('made_to_measure')) ?></th><td><?= e($p['made_to_measure'] ? t('yes') : t('no')) ?></td></tr></table>
    </div>
  </div>
  <?php if ($related): ?><div class="sec"><div class="sec-h"><h2><?= e(t('related')) ?></h2></div><div class="grid"><?php foreach ($related as $x) partial('card', ['p' => $x]); ?></div></div><?php endif; ?>
</section>
