<?php $occ = terms_of('occasion'); $cols = terms_of('color'); $segimg = ['men' => ['#F5F2EA', 'hood'], 'women' => ['#0F7B5F', 'caftan'], 'boys' => ['#7FB2E5', 'hood'], 'girls' => ['#E8A0B4', 'caftan']]; ?>
<section class="hero">
  <div class="wrap hero-in">
    <div class="hero-t">
      <span class="eyebrow"><?= e(setting_l('site_name')) ?></span>
      <h1><?= e(setting_l('hero_title')) ?></h1>
      <p><?= e(setting_l('hero_text')) ?></p>
      <div class="hero-cta"><a class="btn" href="<?= url('shop') ?>"><?= e(t('shop_now')) ?></a><a class="btn btn-ghost" href="<?= url('occasion/wedding') ?>"><?= e(t('wedding_collection')) ?></a></div>
    </div>
    <div class="hero-art" aria-hidden="true">
      <?php if (setting('hero_image')): ?><img src="<?= e(upload_url(setting('hero_image'))) ?>" alt="">
      <?php else: foreach ([['#0F7B5F', 'caftan'], ['#F5F2EA', 'hood'], ['#B5532F', 'hood']] as $i => [$h, $k]): ?><img class="a<?= $i ?>" src="<?= url('ph/' . ltrim($h, '#') . '/' . $k . '.svg') ?>" alt=""><?php endforeach; endif; ?>
    </div>
  </div>
</section>

<section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('shop_by_who')) ?></h2></div>
  <div class="tiles">
    <?php foreach (SEGMENTS as $s): [$h, $k] = $segimg[$s]; ?>
      <a class="tile" href="<?= url('shop/' . $s) ?>"><img src="<?= url('ph/' . ltrim($h, '#') . '/' . $k . '.svg') ?>" alt="" loading="lazy"><span><b><?= e(segment_label($s)) ?></b><small><?= (int)($counts[$s] ?? 0) ?> <?= e(t('items')) ?></small></span></a>
    <?php endforeach; ?>
  </div>
</section>

<section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('shop_by_occasion')) ?></h2></div>
  <div class="chips"><?php foreach ($occ as $o): ?><a class="chip" href="<?= url('occasion/' . $o['slug']) ?>"><?= e(tr($o)) ?></a><?php endforeach; ?></div>
</section>

<?php if ($feat): ?><section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('featured')) ?></h2><a href="<?= url('shop?sort=popular') ?>"><?= e(t('view_all')) ?> ←</a></div>
  <div class="grid"><?php foreach ($feat as $p) partial('card', ['p' => $p]); ?></div>
</section><?php endif; ?>

<section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('shop_by_color')) ?></h2></div>
  <div class="swatches"><?php foreach ($cols as $c): ?><a href="<?= shop_url(['color' => $c['slug']]) ?>" class="sw"><i style="--c:<?= e($c['hex']) ?>"></i><span><?= e(tr($c)) ?></span></a><?php endforeach; ?></div>
</section>

<?php if ($new): ?><section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('new_arrivals')) ?></h2><a href="<?= url('shop') ?>"><?= e(t('view_all')) ?> ←</a></div>
  <div class="grid"><?php foreach ($new as $p) partial('card', ['p' => $p]); ?></div>
</section><?php endif; ?>

<?php if ($sale): ?><section class="wrap sec">
  <div class="sec-h"><h2><?= e(t('sale')) ?></h2><a href="<?= url('sale') ?>"><?= e(t('view_all')) ?> ←</a></div>
  <div class="grid"><?php foreach ($sale as $p) partial('card', ['p' => $p]); ?></div>
</section><?php endif; ?>

<section class="values"><div class="wrap vals">
  <div><b><?= e(t('v1_t')) ?></b><span><?= e(t('v1_d')) ?></span></div>
  <div><b><?= e(t('v2_t')) ?></b><span><?= e(t('v2_d')) ?></span></div>
  <div><b><?= e(t('v3_t')) ?></b><span><?= e(t('v3_d')) ?></span></div>
  <div><b><?= e(t('v4_t')) ?></b><span><?= e(t('v4_d')) ?></span></div>
</div></section>
