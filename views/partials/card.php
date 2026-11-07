<?php /** @var array $p */ $sale = $p['old_price'] && $p['old_price'] > $p['price']; ?>
<article class="card">
  <a href="<?= e(product_url($p)) ?>" class="card-img">
    <img src="<?= e($p['img']) ?>" alt="<?= e(tr($p)) ?>" loading="lazy" width="300" height="400">
    <?php if ($sale): ?><span class="badge badge-sale">-<?= (int)round(100 - $p['price'] / $p['old_price'] * 100) ?>%</span>
    <?php elseif ($p['badge']): ?><span class="badge"><?= e(badge_label($p['badge'])) ?></span><?php endif; ?>
    <?php if ($p['made_to_measure']): ?><span class="badge badge-mtm"><?= e(t('made_to_measure')) ?></span><?php endif; ?>
  </a>
  <div class="card-b">
    <small class="muted"><?= e(tr($p, 'cat')) ?> · <?= e(segment_label($p['segment'])) ?></small>
    <h3><a href="<?= e(product_url($p)) ?>"><?= e(tr($p)) ?></a></h3>
    <div class="price"><b><?= e(money($p['price'])) ?></b><?php if ($sale): ?><s><?= e(money($p['old_price'])) ?></s><?php endif; ?></div>
    <?php if ($p['colors']): ?><div class="dots"><?php foreach (array_slice($p['colors'], 0, 6) as $c): ?><i style="--c:<?= e($c['hex']) ?>" title="<?= e(tr($c)) ?>"></i><?php endforeach; if (count($p['colors']) > 6): ?><em>+<?= count($p['colors']) - 6 ?></em><?php endif; ?></div><?php endif; ?>
  </div>
</article>
