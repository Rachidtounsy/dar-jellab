<a class="logo" href="<?= url() ?>" aria-label="<?= e(setting_l('site_name')) ?>">
  <?php if (setting('logo')): ?><img src="<?= e(upload_url(setting('logo'))) ?>" alt="<?= e(setting_l('site_name')) ?>" height="44">
  <?php else: partial('mark'); ?><span class="logo-t"><b><?= e(setting_l('site_name')) ?></b><small><?= e(lang() === 'ar' ? setting('site_name_fr', 'Dar Jellaba') : setting('site_name_ar', 'دار الجلابة')) ?></small></span><?php endif; ?>
</a>
