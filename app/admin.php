<?php
const BADGES = ['new' => 'جديد', 'sale' => 'تخفيض', 'bestseller' => 'الأكثر مبيعا', 'exclusive' => 'حصري'];
const TERM_TYPES = ['color' => 'الألوان', 'fabric' => 'الأثواب', 'stitch' => 'أنواع الخياطة', 'occasion' => 'المناسبات'];
const SEG_AR = ['men' => 'رجال', 'women' => 'نساء', 'boys' => 'أولاد', 'girls' => 'بنات'];

function adm(string $v, array $d = []): void { view('admin/' . $v, $d, 'admin/layout'); }
function guard(callable $f): callable { return function (array $r = []) use ($f) { require_admin(); $f($r); }; }
function post(string $k, string $d = ''): string { return trim((string)($_POST[$k] ?? $d)); }

function settings_schema(): array {
    return [
        'الهوية' => [['site_name_ar', 'اسم المتجر (عربية)'], ['site_name_fr', 'اسم المتجر (فرنسية)'], ['tagline_ar', 'الشعار النصي (عربية)'], ['tagline_fr', 'الشعار النصي (فرنسية)'],
                     ['logo', 'اللوغو (صورة، اختياري)', 'file'], ['announce_ar', 'شريط الإعلان (عربية)'], ['announce_fr', 'شريط الإعلان (فرنسية)']],
        'الصفحة الرئيسية' => [['hero_title_ar', 'عنوان البانر (عربية)'], ['hero_title_fr', 'عنوان البانر (فرنسية)'], ['hero_text_ar', 'نص البانر (عربية)', 'textarea'], ['hero_text_fr', 'نص البانر (فرنسية)', 'textarea'],
                              ['hero_image', 'صورة البانر (اختياري)', 'file']],
        'التواصل' => [['whatsapp1', 'واتساب 1 (بصيغة دولية، مثال: 212600000000)', 'tel'], ['whatsapp2', 'واتساب 2 (اختياري)', 'tel'], ['phone', 'الهاتف', 'tel'], ['email', 'البريد الإلكتروني', 'email'],
                      ['address_ar', 'العنوان (عربية)', 'textarea'], ['address_fr', 'العنوان (فرنسية)', 'textarea'], ['map_url', 'رابط الخريطة (Google Maps)', 'url'],
                      ['hours_ar', 'أوقات العمل (عربية)'], ['hours_fr', 'أوقات العمل (فرنسية)']],
        'مواقع التواصل الاجتماعي' => [['facebook', 'رابط صفحة فيسبوك', 'url'], ['instagram', 'رابط إنستغرام', 'url'], ['tiktok', 'رابط تيك توك', 'url'], ['youtube', 'رابط يوتيوب', 'url'],
                      ['telegram', 'رابط تيليغرام', 'url'], ['snapchat', 'رابط سناب شات', 'url'], ['pinterest', 'رابط بنتريست', 'url'], ['x', 'رابط X (تويتر)', 'url']],
        'المحتوى' => [['about_ar', 'من نحن (عربية)', 'textarea'], ['about_fr', 'من نحن (فرنسية)', 'textarea'], ['delivery_ar', 'التوصيل (عربية)', 'textarea'], ['delivery_fr', 'التوصيل (فرنسية)', 'textarea']],
        'التتبع' => [['meta_pixel', 'Meta Pixel ID (اختياري)']],
    ];
}

function admin_routes(): void {
    route('GET', '/admin/login', fn() => view('admin/login', [], 'admin/layout_bare'));
    route('POST', '/admin/login', function () {
        csrf_check();
        $_SESSION['tries'] = ($_SESSION['tries'] ?? 0) + 1;
        if ($_SESSION['tries'] > 8) { flash('محاولات كثيرة، حاول لاحقا.', 'err'); redirect('admin/login'); }
        $a = row('SELECT * FROM admins WHERE email=?', [strtolower(post('email'))]);
        if ($a && password_verify(post('password'), $a['pass'])) { session_regenerate_id(true); $_SESSION['admin_id'] = (int)$a['id']; $_SESSION['tries'] = 0; redirect('admin'); }
        flash('البريد أو كلمة المرور غير صحيحة.', 'err'); redirect('admin/login');
    });
    route('GET', '/admin/logout', function () { unset($_SESSION['admin_id']); redirect('admin/login'); });
    route('GET', '/admin', guard(function () {
        adm('dashboard', ['n' => ['products' => val('SELECT COUNT(*) FROM products'), 'active' => val('SELECT COUNT(*) FROM products WHERE active=1'),
            'out' => val('SELECT COUNT(*) FROM products p WHERE active=1 AND COALESCE((SELECT SUM(stock) FROM variants WHERE product_id=p.id),0)=0')],
            'top' => rows('SELECT id,name_ar,views FROM products ORDER BY views DESC LIMIT 5'), 'wa' => wa_number()]);
    }));
    route('GET', '/admin/settings', guard(fn() => adm('settings', ['schema' => settings_schema()])));
    route('POST', '/admin/settings', guard(function () {
        foreach (settings_schema() as $fields) foreach ($fields as $f) {
            [$k, , $type] = $f + [2 => 'text'];
            if ($type === 'file') {
                if (!empty($_POST['rm_' . $k])) { delete_image(setting($k)); q('REPLACE INTO settings (k,v) VALUES (?,?)', [$k, '']); }
                if (!empty($_FILES[$k]['name']) && ($p = save_image($_FILES[$k], 'site', 1600))) { delete_image(setting($k)); q('REPLACE INTO settings (k,v) VALUES (?,?)', [$k, $p]); }
                continue;
            }
            $v = post($k);
            if (in_array($type, ['url']) && $v !== '' && !preg_match('#^https?://#i', $v)) $v = 'https://' . $v;
            q('REPLACE INTO settings (k,v) VALUES (?,?)', [$k, $v]);
        }
        flash('تم حفظ الإعدادات.'); redirect('admin/settings');
    }));

    /* ---- Produits ---- */
    route('GET', '/admin/products', guard(function () {
        $w = ['1=1']; $a = [];
        if (!empty($_GET['q'])) { $w[] = '(p.name_ar LIKE ? OR p.name_fr LIKE ?)'; $a[] = $a[] = '%' . $_GET['q'] . '%'; }
        if (!empty($_GET['segment'])) { $w[] = 'p.segment=?'; $a[] = $_GET['segment']; }
        $ps = rows('SELECT p.*, c.name_ar AS cat_ar, (SELECT SUM(stock) FROM variants WHERE product_id=p.id) AS stock, (SELECT path FROM images WHERE product_id=p.id ORDER BY sort,id LIMIT 1) AS img
                    FROM products p JOIN categories c ON c.id=p.category_id WHERE ' . implode(' AND ', $w) . ' ORDER BY p.id DESC LIMIT 300', $a);
        adm('products', ['ps' => $ps]);
    }));
    route('GET', '/admin/products/new', guard(fn() => adm('product_form', product_form_data(null))));
    route('POST', '/admin/products/new', guard(fn() => product_save(null)));
    route('GET', '/admin/products/{id}', guard(function ($r) { $p = row('SELECT * FROM products WHERE id=?', [(int)$r['id']]); $p || not_found(); adm('product_form', product_form_data($p)); }));
    route('POST', '/admin/products/{id}', guard(function ($r) {
        $p = row('SELECT * FROM products WHERE id=?', [(int)$r['id']]); $p || not_found();
        if (post('action') === 'delete') {
            foreach (rows('SELECT path FROM images WHERE product_id=?', [$p['id']]) as $i) delete_image($i['path']);
            foreach (['images', 'variants', 'product_terms'] as $t) q("DELETE FROM $t WHERE product_id=?", [$p['id']]);
            q('DELETE FROM products WHERE id=?', [$p['id']]); flash('تم حذف المنتج.'); redirect('admin/products');
        }
        product_save($p);
    }));

    /* ---- Listes : couleurs, tissus, coutures, occasions, catégories ---- */
    route('GET', '/admin/terms', guard(function () {
        $type = array_key_exists($_GET['type'] ?? '', TERM_TYPES) ? $_GET['type'] : 'color';
        adm('terms', ['type' => $type, 'items' => rows('SELECT t.*, (SELECT COUNT(*) FROM variants WHERE color_id=t.id) + (SELECT COUNT(*) FROM product_terms WHERE term_id=t.id) + (SELECT COUNT(*) FROM products WHERE fabric_id=t.id OR stitch_id=t.id) AS used FROM terms t WHERE type=? ORDER BY sort,id', [$type])]);
    }));
    route('POST', '/admin/terms', guard(function () {
        $type = array_key_exists(post('type'), TERM_TYPES) ? post('type') : 'color'; $act = post('action');
        if ($act === 'add') {
            $slug = slugify(post('name_fr') ?: post('name_ar')); if (val('SELECT 1 FROM terms WHERE type=? AND slug=?', [$type, $slug])) $slug .= '-' . random_int(10, 99);
            q('INSERT INTO terms (type,slug,name_ar,name_fr,hex,sort) VALUES (?,?,?,?,?,?)', [$type, $slug, post('name_ar'), post('name_fr'), $type === 'color' ? post('hex', '#999999') : null, (int)val('SELECT COALESCE(MAX(sort),0)+1 FROM terms WHERE type=?', [$type])]);
            flash('تمت الإضافة.');
        } elseif ($act === 'save') {
            foreach ((array)($_POST['t'] ?? []) as $id => $t)
                q('UPDATE terms SET name_ar=?, name_fr=?, hex=?, sort=?, active=? WHERE id=? AND type=?', [trim($t['name_ar']), trim($t['name_fr']), $type === 'color' ? ($t['hex'] ?? null) : null, (int)$t['sort'], isset($t['active']) ? 1 : 0, (int)$id, $type]);
            flash('تم الحفظ.');
        } elseif ($act === 'delete') {
            $id = (int)post('id');
            if (val('SELECT COUNT(*) FROM variants WHERE color_id=?', [$id]) || val('SELECT COUNT(*) FROM product_terms WHERE term_id=?', [$id]) || val('SELECT COUNT(*) FROM products WHERE fabric_id=? OR stitch_id=?', [$id, $id])) flash('العنصر مستعمل فمنتجات، عطله بدل الحذف.', 'err');
            else { q('DELETE FROM terms WHERE id=? AND type=?', [$id, $type]); flash('تم الحذف.'); }
        }
        redirect('admin/terms?type=' . $type);
    }));
    route('GET', '/admin/categories', guard(fn() => adm('categories', ['items' => rows('SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id=c.id) AS used FROM categories c ORDER BY segment, sort, id')])));
    route('POST', '/admin/categories', guard(function () {
        $act = post('action');
        if ($act === 'add' && isset(SEG_AR[post('segment')])) {
            $slug = slugify(post('name_fr') ?: post('name_ar')); if (val('SELECT 1 FROM categories WHERE segment=? AND slug=?', [post('segment'), $slug])) $slug .= '-' . random_int(10, 99);
            q('INSERT INTO categories (segment,slug,name_ar,name_fr,accessory,sort) VALUES (?,?,?,?,?,?)', [post('segment'), $slug, post('name_ar'), post('name_fr'), isset($_POST['accessory']) ? 1 : 0, 99]); flash('تمت الإضافة.');
        } elseif ($act === 'save') {
            foreach ((array)($_POST['c'] ?? []) as $id => $c)
                q('UPDATE categories SET name_ar=?, name_fr=?, accessory=?, sort=?, active=? WHERE id=?', [trim($c['name_ar']), trim($c['name_fr']), isset($c['accessory']) ? 1 : 0, (int)$c['sort'], isset($c['active']) ? 1 : 0, (int)$id]);
            flash('تم الحفظ.');
        } elseif ($act === 'delete') {
            if (val('SELECT COUNT(*) FROM products WHERE category_id=?', [(int)post('id')])) flash('الصنف فيه منتجات، عطله بدل الحذف.', 'err');
            else { q('DELETE FROM categories WHERE id=?', [(int)post('id')]); flash('تم الحذف.'); }
        }
        redirect('admin/categories');
    }));
    route('GET', '/admin/password', guard(fn() => adm('password')));
    route('POST', '/admin/password', guard(function () {
        $a = row('SELECT * FROM admins WHERE id=?', [$_SESSION['admin_id']]);
        if (!password_verify(post('old'), $a['pass'])) flash('كلمة المرور الحالية غير صحيحة.', 'err');
        elseif (strlen(post('new')) < 8) flash('كلمة المرور الجديدة قصيرة (8 على الأقل).', 'err');
        else { q('UPDATE admins SET pass=? WHERE id=?', [password_hash(post('new'), PASSWORD_DEFAULT), $a['id']]); flash('تم تغيير كلمة المرور.'); }
        redirect('admin/password');
    }));
}

function product_form_data(?array $p): array {
    $id = $p['id'] ?? 0;
    return ['p' => $p ?? ['segment' => 'men', 'category_id' => 0, 'name_ar' => '', 'name_fr' => '', 'desc_ar' => '', 'desc_fr' => '', 'price' => '', 'old_price' => '', 'fabric_id' => null, 'stitch_id' => null,
                          'made_to_measure' => 0, 'badge' => '', 'featured' => 0, 'active' => 1],
        'cats' => rows('SELECT * FROM categories ORDER BY segment, sort'), 'colors' => rows('SELECT * FROM terms WHERE type=\'color\' ORDER BY sort'),
        'fabrics' => rows('SELECT * FROM terms WHERE type=\'fabric\' ORDER BY sort'), 'stitches' => rows('SELECT * FROM terms WHERE type=\'stitch\' ORDER BY sort'), 'occasions' => rows('SELECT * FROM terms WHERE type=\'occasion\' ORDER BY sort'),
        'vars' => $id ? rows('SELECT * FROM variants WHERE product_id=? ORDER BY id', [$id]) : [], 'imgs' => $id ? rows('SELECT * FROM images WHERE product_id=? ORDER BY sort,id', [$id]) : [],
        'occ' => $id ? array_column(rows('SELECT term_id FROM product_terms WHERE product_id=?', [$id]), 'term_id') : []];
}

function product_save(?array $p): void {
    $err = null;
    $cat = row('SELECT * FROM categories WHERE id=?', [(int)post('category_id')]);
    if (post('name_ar') === '' || !$cat) $err = 'الاسم بالعربية والصنف إجباريان.';
    elseif ((int)post('price') <= 0) $err = 'الثمن إجباري.';
    if ($err) { flash($err, 'err'); redirect($p ? 'admin/products/' . $p['id'] : 'admin/products/new'); }
    $pdo = db(); $pdo->beginTransaction();
    try {
        $f = [$cat['segment'], $cat['id'], post('name_ar'), post('name_fr'), post('desc_ar'), post('desc_fr'), (int)post('price'), post('old_price') !== '' ? (int)post('old_price') : null,
              (int)post('fabric_id') ?: null, (int)post('stitch_id') ?: null, isset($_POST['made_to_measure']) ? 1 : 0, isset(BADGES[post('badge')]) ? post('badge') : null, isset($_POST['featured']) ? 1 : 0, isset($_POST['active']) ? 1 : 0];
        if ($p) { q('UPDATE products SET segment=?,category_id=?,name_ar=?,name_fr=?,desc_ar=?,desc_fr=?,price=?,old_price=?,fabric_id=?,stitch_id=?,made_to_measure=?,badge=?,featured=?,active=? WHERE id=?', [...$f, $p['id']]); $id = (int)$p['id']; }
        else {
            $slug = slugify(post('name_fr') ?: post('name_ar')); while (val('SELECT 1 FROM products WHERE slug=?', [$slug])) $slug .= '-' . random_int(10, 99);
            q('INSERT INTO products (segment,category_id,name_ar,name_fr,desc_ar,desc_fr,price,old_price,fabric_id,stitch_id,made_to_measure,badge,featured,active,slug) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [...$f, $slug]);
            $id = (int)$pdo->lastInsertId();
        }
        q('DELETE FROM product_terms WHERE product_id=?', [$id]);
        foreach ((array)($_POST['occ'] ?? []) as $t) q('INSERT INTO product_terms (product_id,term_id) VALUES (?,?)', [$id, (int)$t]);
        q('DELETE FROM variants WHERE product_id=?', [$id]);
        foreach ((array)($_POST['v'] ?? []) as $v) if (trim($v['size'] ?? '') !== '' || !empty($v['color'])) q('INSERT INTO variants (product_id,color_id,size,stock) VALUES (?,?,?,?)', [$id, (int)$v['color'] ?: null, trim($v['size']), max(0, (int)$v['stock'])]);
        foreach ((array)($_POST['img_del'] ?? []) as $iid) { $i = row('SELECT * FROM images WHERE id=? AND product_id=?', [(int)$iid, $id]); if ($i) { delete_image($i['path']); q('DELETE FROM images WHERE id=?', [$i['id']]); } }
        foreach ((array)($_POST['img_sort'] ?? []) as $iid => $s) q('UPDATE images SET sort=? WHERE id=? AND product_id=?', [(int)$s, (int)$iid, $id]);
        if (!empty($_FILES['images']['name'][0])) {
            $n = (int)val('SELECT COALESCE(MAX(sort),0) FROM images WHERE product_id=?', [$id]);
            foreach ($_FILES['images']['name'] as $k => $_) {
                $one = ['name' => $_FILES['images']['name'][$k], 'tmp_name' => $_FILES['images']['tmp_name'][$k], 'error' => $_FILES['images']['error'][$k]];
                if ($path = save_image($one)) q('INSERT INTO images (product_id,path,sort) VALUES (?,?,?)', [$id, $path, ++$n]);
            }
        }
        $pdo->commit();
    } catch (Throwable $e) { $pdo->rollBack(); flash('خطأ: ' . $e->getMessage(), 'err'); redirect($p ? 'admin/products/' . $p['id'] : 'admin/products/new'); }
    flash('تم حفظ المنتج.'); redirect('admin/products/' . $id);
}
