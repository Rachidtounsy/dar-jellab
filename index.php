<?php
declare(strict_types=1);
require __DIR__ . '/app/bootstrap.php';
if (!is_array($GLOBALS['CFG'])) { header('Location: install.php'); exit; }
require ROOT . '/app/router.php';
require ROOT . '/app/controllers.php';
require ROOT . '/app/admin.php';

route('GET', '/', 'page_home');
route('GET', '/shop', 'page_shop');
route('GET', '/shop/{segment}', 'page_shop');
route('GET', '/shop/{segment}/{cat}', 'page_shop');
route('GET', '/accessories', fn() => page_shop([], ['accessory' => 1, '_path' => 'accessories']));
route('GET', '/sale', fn() => page_shop([], ['sale' => 1, '_path' => 'sale']));
route('GET', '/occasion/{slug}', fn($r) => page_shop([], ['occasion' => $r['slug'], '_path' => 'occasion/' . $r['slug']]));
route('GET', '/search', fn() => page_shop([], ['_path' => 'search']));
route('GET', '/p/{slug}', 'page_product');
route('GET', '/about', fn() => page_static('about'));
route('GET', '/contact', fn() => page_static('contact'));
route('GET', '/delivery', fn() => page_static('delivery'));
route('GET', '/ph/{hex}/{kind}', 'page_placeholder');
route('GET', '/lang/{l}', 'page_lang');
route('GET', '/sitemap.xml', 'page_sitemap');
admin_routes();
dispatch();
