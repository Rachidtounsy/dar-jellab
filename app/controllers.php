<?php
function page_home(): void {
    $featured = fetch_products(['sort' => 'popular'] + [], 8);
    $feat = array_values(array_filter(fetch_products([], 60), fn($p) => $p['featured']));
    $new = fetch_products(['sort' => 'new'], 8);
    $sale = fetch_products(['sale' => 1], 4);
    $counts = []; foreach (rows('SELECT segment, COUNT(*) n FROM products WHERE active=1 GROUP BY segment') as $r) $counts[$r['segment']] = $r['n'];
    view('shop/home', ['title' => setting_l('site_name') . ' — ' . setting_l('tagline'), 'feat' => array_slice($feat ?: $featured, 0, 8), 'new' => $new, 'sale' => $sale, 'counts' => $counts]);
}

function page_shop(array $r = [], array $force = []): void {
    $f = filters_from_query($_GET) + $force;
    foreach (['segment', 'cat'] as $k) if (isset($r[$k])) $f[$k] = $r[$k];
    if (isset($f['segment']) && !in_array($f['segment'], SEGMENTS, true)) not_found();
    $per = 24; $page = max(1, (int)($f['page'] ?? 1));
    $total = count_products($f);
    $products = fetch_products($f, $per, ($page - 1) * $per);
    $title = t('all_products'); $crumbs = [];
    if (!empty($f['segment'])) { $title = segment_label($f['segment']); }
    if (!empty($f['segment']) && !empty($f['cat'])) {
        $c = row('SELECT * FROM categories WHERE segment=? AND slug=?', [$f['segment'], $f['cat']]);
        if (!$c) not_found();
        $title = tr($c) . ' — ' . segment_label($f['segment']);
    }
    if (!empty($f['accessory'])) $title = t('accessories');
    if (!empty($f['sale'])) $title = t('sale');
    if (!empty($f['occasion']) && ($o = term_by('occasion', $f['occasion']))) $title = tr($o);
    if (!empty($f['q'])) $title = t('search') . ': ' . $f['q'];
    view('shop/shop', ['title' => $title, 'f' => $f, 'products' => $products, 'total' => $total, 'page' => $page, 'pages' => (int)ceil($total / $per),
        'tree' => nav_tree(), 'path' => $force ? ($force['_path'] ?? 'shop') : 'shop' . (isset($r['segment']) ? '/' . $r['segment'] : '') . (isset($r['cat']) ? '/' . $r['cat'] : '')]);
}

function page_product(array $r): void {
    $p = row('SELECT p.*, c.name_ar AS cat_ar, c.name_fr AS cat_fr, c.slug AS cat_slug FROM products p JOIN categories c ON c.id=p.category_id WHERE p.slug=? AND p.active=1', [$r['slug']]);
    if (!$p) not_found();
    if (empty($_SESSION['seen'][$p['id']])) { q('UPDATE products SET views=views+1 WHERE id=?', [$p['id']]); $_SESSION['seen'][$p['id']] = 1; }
    $vars = rows('SELECT v.*, t.name_ar, t.name_fr, t.hex FROM variants v LEFT JOIN terms t ON t.id=v.color_id WHERE v.product_id=? ORDER BY t.sort, v.id', [$p['id']]);
    $colors = [];
    foreach ($vars as $v) { $k = (int)$v['color_id']; $colors[$k] ??= ['id' => $k, 'name' => tr($v), 'hex' => $v['hex'] ?: '#E8DCC2', 'sizes' => []]; $colors[$k]['sizes'][] = ['size' => $v['size'], 'stock' => (int)$v['stock']]; }
    $images = rows('SELECT * FROM images WHERE product_id=? ORDER BY sort, id', [$p['id']]);
    $terms = rows('SELECT t.* FROM product_terms pt JOIN terms t ON t.id=pt.term_id WHERE pt.product_id=? ORDER BY t.sort', [$p['id']]);
    $fabric = $p['fabric_id'] ? row('SELECT * FROM terms WHERE id=?', [$p['fabric_id']]) : null;
    $stitch = $p['stitch_id'] ? row('SELECT * FROM terms WHERE id=?', [$p['stitch_id']]) : null;
    $related = array_slice(array_filter(fetch_products(['segment' => $p['segment'], 'cat' => $p['cat_slug']], 9), fn($x) => $x['id'] != $p['id']), 0, 4);
    $first = $colors ? array_values($colors)[0]['hex'] : '#E8DCC2';
    view('shop/product', ['title' => tr($p), 'p' => $p, 'colors' => array_values($colors), 'images' => $images, 'terms' => $terms, 'fabric' => $fabric, 'stitch' => $stitch,
        'related' => $related, 'ph' => placeholder_url($p, $first)]);
}

function page_static(string $k): void { view('shop/static_' . $k, ['title' => t('page_' . $k)]); }

function page_placeholder(array $r): void {
    header('Content-Type: image/svg+xml'); header('Cache-Control: public, max-age=31536000');
    $k = preg_replace('/\.svg$/', '', $r['kind']);
    echo placeholder_svg($r['hex'], in_array($k, ['hood', 'caftan', 'cap', 'shoe'], true) ? $k : 'hood');
}

function page_lang(array $r): void {
    if (in_array($r['l'], ['ar', 'fr'], true)) setcookie('lang', $r['l'], ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $host = parse_url($ref, PHP_URL_HOST);
    redirect($host && $host === ($_SERVER['HTTP_HOST'] ?? '') || ($host && str_starts_with($_SERVER['HTTP_HOST'] ?? '', $host)) ? $ref : '');
}

function page_sitemap(): void {
    header('Content-Type: application/xml; charset=utf-8');
    $abs = fn($p) => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $p;
    echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach (['', '/shop', '/sale', '/about', '/contact'] as $u) echo '<url><loc>' . e($abs(url($u))) . '</loc></url>';
    foreach (rows('SELECT slug FROM products WHERE active=1') as $p) echo '<url><loc>' . e($abs(url('p/' . $p['slug']))) . '</loc></url>';
    echo '</urlset>';
}
