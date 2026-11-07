<form class="login" method="post"><?= csrf_field() ?><div class="login-mark"><?php partial('mark'); ?></div><h1>لوحة تحكم <?= e(setting_l('site_name')) ?></h1>
<label>البريد الإلكتروني<input type="email" name="email" required autofocus autocomplete="username"></label>
<label>كلمة المرور<input type="password" name="password" required autocomplete="current-password"></label><button class="btn lg">دخول</button></form>
