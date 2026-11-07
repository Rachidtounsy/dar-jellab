<?php
/* ---- Données du catalogue ---- */
function segment_label(string $s): string { return t('seg_' . $s); }
function nav_tree(): array {
    static $t;
    if ($t !== null) return $t;
    $t = [];
    foreach (rows('SELECT * FROM categories WHERE active=1 ORDER BY segment, sort, id') as $c) $t[$c['segment']][] = $c;
    return $t;
}
function terms_of(string $type): array { static $c = []; return $c[$type] ??= rows('SELECT * FROM terms WHERE type=? AND active=1 ORDER BY sort, id', [$type]); }
function term_by(string $type, string $slug): ?array { return row('SELECT * FROM terms WHERE type=? AND slug=?', [$type, $slug]); }

function filters_from_query(array $g): array {
    $f = [];
    foreach (['segment', 'cat', 'color', 'fabric', 'stitch', 'occasion', 'q', 'sort', 'badge'] as $k) if (isset($g[$k]) && $g[$k] !== '') $f[$k] = trim((string)$g[$k]);
    foreach (['min', 'max', 'page'] as $k) if (isset($g[$k]) && (int)$g[$k] > 0) $f[$k] = (int)$g[$k];
    if (!empty($g['accessory'])) $f['accessory'] = 1;
    if (!empty($g['sale'])) $f['sale'] = 1;
    return $f;
}

function product_where(array $f): array {
    $w = ['p.active=1']; $a = [];
    if (!empty($f['segment'])) { $w[] = 'p.segment=?'; $a[] = $f['segment']; }
    if (!empty($f['cat'])) { $w[] = 'c.slug=?'; $a[] = $f['cat']; }
    if (!empty($f['accessory'])) $w[] = 'c.accessory=1';
    if (!empty($f['sale'])) $w[] = 'p.old_price > p.price';
    if (!empty($f['badge'])) { $w[] = 'p.badge=?'; $a[] = $f['badge']; }
    if (!empty($f['fabric'])) { $w[] = 'p.fabric_id=(SELECT id FROM terms WHERE type=\'fabric\' AND slug=?)'; $a[] = $f['fabric']; }
    if (!empty($f['stitch'])) { $w[] = 'p.stitch_id=(SELECT id FROM terms WHERE type=\'stitch\' AND slug=?)'; $a[] = $f['stitch']; }
    if (!empty($f['color'])) { $w[] = 'EXISTS (SELECT 1 FROM variants v JOIN terms t ON t.id=v.color_id WHERE v.product_id=p.id AND t.slug=?)'; $a[] = $f['color']; }
    if (!empty($f['occasion'])) { $w[] = 'EXISTS (SELECT 1 FROM product_terms pt JOIN terms t ON t.id=pt.term_id WHERE pt.product_id=p.id AND t.type=\'occasion\' AND t.slug=?)'; $a[] = $f['occasion']; }
    if (!empty($f['min'])) { $w[] = 'p.price>=?'; $a[] = $f['min']; }
    if (!empty($f['max'])) { $w[] = 'p.price<=?'; $a[] = $f['max']; }
    if (!empty($f['q'])) { $w[] = '(p.name_ar LIKE ? OR p.name_fr LIKE ? OR c.name_ar LIKE ? OR c.name_fr LIKE ?)'; $l = '%' . $f['q'] . '%'; array_push($a, $l, $l, $l, $l); }
    return [implode(' AND ', $w), $a];
}

function count_products(array $f): int {
    [$w, $a] = product_where($f);
    return (int)val("SELECT COUNT(*) FROM products p JOIN categories c ON c.id=p.category_id WHERE $w", $a);
}

function fetch_products(array $f, int $limit = 24, int $offset = 0): array {
    [$w, $a] = product_where($f);
    $order = match ($f['sort'] ?? 'new') { 'price_asc' => 'p.price ASC', 'price_desc' => 'p.price DESC', 'popular' => 'p.views DESC, p.id DESC', default => 'p.id DESC' };
    $ps = rows("SELECT p.*, c.name_ar AS cat_ar, c.name_fr AS cat_fr, c.slug AS cat_slug FROM products p JOIN categories c ON c.id=p.category_id
                WHERE $w ORDER BY $order LIMIT " . (int)$limit . ' OFFSET ' . (int)$offset, $a);
    return decorate_cards($ps);
}

/** ajoute image principale + pastilles de couleurs */
function decorate_cards(array $ps): array {
    if (!$ps) return [];
    $ids = implode(',', array_map(fn($p) => (int)$p['id'], $ps));
    $img = []; foreach (rows("SELECT product_id, path FROM images WHERE product_id IN ($ids) ORDER BY sort, id") as $r) $img[$r['product_id']] ??= $r['path'];
    $col = [];
    foreach (rows("SELECT DISTINCT v.product_id, t.id, t.name_ar, t.name_fr, t.hex, t.sort FROM variants v JOIN terms t ON t.id=v.color_id WHERE v.product_id IN ($ids) ORDER BY t.sort") as $r) $col[$r['product_id']][] = $r;
    foreach ($ps as &$p) {
        $p['colors'] = $col[$p['id']] ?? [];
        $p['img'] = isset($img[$p['id']]) ? upload_url(thumb_path($img[$p['id']])) : placeholder_url($p, $p['colors'][0]['hex'] ?? '#E8DCC2');
    }
    return $ps;
}

function placeholder_url(array $p, string $hex): string {
    $kind = match (true) {
        in_array($p['cat_slug'] ?? '', ['caftan', 'takchita']) => 'caftan',
        ($p['cat_slug'] ?? '') === 'tarbouch' => 'cap',
        in_array($p['cat_slug'] ?? '', ['belgha']) => 'shoe',
        default => 'hood',
    };
    return url('ph/' . ltrim($hex, '#') . '/' . $kind . '.svg');
}
function product_url(array $p): string { return url('p/' . $p['slug']); }
function shop_url(array $q = [], string $path = 'shop'): string {
    $q = array_filter($q, fn($v) => $v !== '' && $v !== null);
    return url($path) . ($q ? '?' . http_build_query($q) : '');
}
function badge_label(?string $b): string { return $b ? t('badge_' . $b) : ''; }

/** silhouette SVG de remplacement (sans photo) teintée de la couleur du produit */
function placeholder_svg(string $hex, string $kind): string {
    $hex = '#' . preg_replace('/[^0-9a-fA-F]/', '', $hex);
    [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x') + [0, 0, 0];
    $lum = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    $trim = $lum > 0.62 ? '#1F3A5F' : '#E3C874';
    $bg1 = '#F6F0E6'; $bg2 = '#EADFCB';
    $stroke = $lum > 0.85 ? ' stroke="#D8CCB4" stroke-width="2"' : '';
    $shape = match ($kind) {
        'caftan' => "<path d='M150 50C130 50 118 62 112 80L66 112C54 120 50 134 52 150L58 200L96 196L92 372H208L204 196L242 200L248 150C250 134 246 120 234 112L188 80C182 62 170 50 150 50Z' fill='$hex'$stroke/>
                     <path d='M126 68L150 128L174 68Z' fill='$bg1'/><path d='M150 128V372' stroke='$trim' stroke-width='5' stroke-dasharray='1 9' stroke-linecap='round'/>
                     <rect x='92' y='214' width='116' height='14' fill='$trim'/><path d='M92 366H208' stroke='$trim' stroke-width='5'/><path d='M54 146L60 196' stroke='$trim' stroke-width='5'/><path d='M246 146L240 196' stroke='$trim' stroke-width='5'/>",
        'cap' => "<path d='M96 300L112 120C114 104 124 96 150 96C176 96 186 104 188 120L204 300Z' fill='$hex'$stroke/><path d='M150 96V60' stroke='$trim' stroke-width='6' stroke-linecap='round'/><circle cx='150' cy='54' r='9' fill='$trim'/>",
        'shoe' => "<path d='M50 300C50 250 90 230 130 238C176 246 214 250 250 280C256 296 246 312 228 312H66C56 312 50 308 50 300Z' fill='$hex'$stroke/><path d='M60 300H240' stroke='$trim' stroke-width='5'/><path d='M96 250C120 262 150 262 176 252' stroke='$trim' stroke-width='5' fill='none' stroke-linecap='round'/>",
        default => "<path d='M150 40C170 52 182 70 184 92L236 118C250 126 256 140 258 160L266 372H34L42 160C44 140 50 126 64 118L116 92C118 70 130 52 150 40Z' fill='$hex'$stroke/>
                    <ellipse cx='150' cy='96' rx='20' ry='26' fill='$bg1'/><path d='M150 124V372' stroke='$trim' stroke-width='5' stroke-dasharray='1 9' stroke-linecap='round'/>
                    <path d='M38 352H262' stroke='$trim' stroke-width='5'/><path d='M40 340H260' stroke='$trim' stroke-width='2'/><path d='M44 150L50 354' stroke='$trim' stroke-width='3' opacity='.6'/><path d='M256 150L250 354' stroke='$trim' stroke-width='3' opacity='.6'/>",
    };
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 400'><defs><linearGradient id='g' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='$bg1'/><stop offset='1' stop-color='$bg2'/></linearGradient></defs><rect width='300' height='400' fill='url(#g)'/>$shape</svg>";
}
