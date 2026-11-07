<section class="wrap pad narrow"><h1><?= e(t('page_contact')) ?></h1>
<div class="contact">
<?php foreach (['whatsapp1', 'whatsapp2'] as $k) if ($n = setting($k)): ?><a class="btn btn-wa" href="<?= e(wa_link(t('wa_hello'), preg_replace('/\D+/', '', $n))) ?>" target="_blank" rel="noopener" dir="ltr">WhatsApp <?= e($n) ?></a><?php endif; ?>
<?php if ($x = setting('phone')): ?><p><b><?= e(t('phone')) ?>:</b> <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $x)) ?>" dir="ltr"><?= e($x) ?></a></p><?php endif; ?>
<?php if ($x = setting('email')): ?><p><b>Email:</b> <a href="mailto:<?= e($x) ?>"><?= e($x) ?></a></p><?php endif; ?>
<?php if ($x = setting_l('address')): ?><p><b><?= e(t('address')) ?>:</b><br><?= nl2br(e($x)) ?></p><?php endif; ?>
<?php if ($x = setting('map_url')): ?><p><a href="<?= e($x) ?>" target="_blank" rel="noopener"><?= e(t('map')) ?> ←</a></p><?php endif; ?>
<?php if ($x = setting_l('hours')): ?><p><b><?= e(t('hours')) ?>:</b> <?= e($x) ?></p><?php endif; ?>
<?php foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'telegram' => 'Telegram', 'snapchat' => 'Snapchat', 'pinterest' => 'Pinterest', 'x' => 'X'] as $k => $l) if ($u = setting($k)): ?><a class="chip" href="<?= e($u) ?>" target="_blank" rel="noopener"><?= $l ?></a> <?php endif; ?>
</div></section>
