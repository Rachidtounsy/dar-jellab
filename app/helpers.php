<?php
const SEGMENTS = ['men', 'women', 'boys', 'girls'];

function cfg(string $k, $d = null) { global $CFG; return is_array($CFG) && array_key_exists($k, $CFG) ? $CFG[$k] : $d; }
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function base(): string { return rtrim((string)cfg('base_url', ''), '/'); }
function url(string $p = ''): string { return base() . '/' . ltrim($p, '/'); }
function asset(string $p): string { $f = ROOT . '/assets/' . $p; return url('assets/' . $p) . (is_file($f) ? '?v=' . filemtime($f) : ''); }
function upload_url(?string $p): string { return $p ? url('uploads/' . ltrim($p, '/')) : ''; }
function redirect(string $p, int $code = 302): never {
    header('Location: ' . (preg_match('#^https?://#', $p) ? $p : url($p)), true, $code); exit;
}

/* ---------- DB ---------- */
function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    if (cfg('db_driver', 'mysql') === 'sqlite') {
        $pdo = new PDO('sqlite:' . cfg('db_sqlite'));
        $pdo->exec('PRAGMA foreign_keys=ON');
    } else {
        $pdo = new PDO('mysql:host=' . cfg('db_host') . ';dbname=' . cfg('db_name') . ';charset=utf8mb4', cfg('db_user'), cfg('db_pass'));
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}
function q(string $sql, array $p = []): PDOStatement { $s = db()->prepare($sql); $s->execute($p); return $s; }
function rows(string $sql, array $p = []): array { return q($sql, $p)->fetchAll(); }
function row(string $sql, array $p = []): ?array { $r = q($sql, $p)->fetch(); return $r ?: null; }
function val(string $sql, array $p = []) { $r = q($sql, $p)->fetch(PDO::FETCH_NUM); return $r ? $r[0] : null; }
function now(): string { return date('Y-m-d H:i:s'); }

/* ---------- i18n ---------- */
function lang(): string { $l = $_COOKIE['lang'] ?? 'ar'; return in_array($l, ['ar', 'fr'], true) ? $l : 'ar'; }
function dir_(): string { return lang() === 'ar' ? 'rtl' : 'ltr'; }
function t(string $k, array $r = []): string {
    static $T = [];
    $l = lang();
    if (!isset($T[$l])) $T[$l] = require ROOT . "/lang/$l.php";
    $s = $T[$l][$k] ?? $k;
    return $r ? strtr($s, $r) : $s;
}
/** nom localisé d'une ligne (name_ar / name_fr) */
function tr(array $r, string $f = 'name'): string { $l = lang(); return (string)(($r[$f . '_' . $l] ?? '') ?: ($r[$f . '_ar'] ?? ($r[$f . '_fr'] ?? ''))); }

/* ---------- Settings (modifiables depuis l'admin) ---------- */
function settings(): array {
    static $S;
    if ($S === null) {
        try { $S = array_column(rows('SELECT k, v FROM settings'), 'v', 'k'); } catch (Throwable $e) { $S = []; }
    }
    return $S;
}
function setting(string $k, string $d = ''): string { $v = settings()[$k] ?? ''; return $v !== '' ? $v : $d; }
function setting_l(string $k, string $d = ''): string { return setting($k . '_' . lang(), setting($k . '_ar', $d)); }
function wa_number(): string { return preg_replace('/\D+/', '', setting('whatsapp1')); }
function wa_link(string $msg = '', ?string $num = null): string {
    $n = $num ?? wa_number();
    return 'https://wa.me/' . $n . ($msg !== '' ? '?text=' . rawurlencode($msg) : '');
}
function money($n): string { return number_format((float)$n, 0, '.', ' ') . ' ' . (lang() === 'ar' ? 'د.م' : 'MAD'); }

/* ---------- Sécurité ---------- */
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="' . e(csrf()) . '">'; }
function csrf_check(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals(csrf(), (string)($_POST['_csrf'] ?? ''))) { http_response_code(419); exit('CSRF'); }
}
function flash(?string $m = null, string $type = 'ok') {
    if ($m !== null) { $_SESSION['flash'] = [$m, $type]; return null; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function slugify(string $s): string {
    $s = mb_strtolower(trim($s));
    $s = preg_replace('/[^\p{L}\p{N}]+/u', '-', $s);
    return trim($s, '-') ?: bin2hex(random_bytes(3));
}
function is_admin(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!is_admin()) redirect('admin/login'); csrf_check(); }

/* ---------- Images ---------- */
function thumb_path(string $p): string { return preg_replace('/\.(webp|jpg)$/', '_t.$1', $p); }
function save_image(array $f, string $dir = 'products', int $max = 1400): ?string {
    if (($f['error'] ?? 1) !== UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name'])) return null;
    $info = @getimagesize($f['tmp_name']);
    if (!$info) return null;
    $img = match ($info[2]) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($f['tmp_name']),
        IMAGETYPE_PNG  => @imagecreatefrompng($f['tmp_name']),
        IMAGETYPE_WEBP => @imagecreatefromwebp($f['tmp_name']),
        default => false,
    };
    if (!$img) return null;
    $ext = function_exists('imagewebp') ? 'webp' : 'jpg';
    $d = ROOT . '/uploads/' . $dir; if (!is_dir($d)) mkdir($d, 0755, true);
    $name = date('ym') . '-' . bin2hex(random_bytes(5)) . '.' . $ext;
    $out = function ($im, $path) use ($ext) { $ext === 'webp' ? imagewebp($im, $path, 82) : imagejpeg($im, $path, 85); };
    foreach ([[$max, $name], [480, thumb_path($name)]] as [$w, $n]) {
        $ow = imagesx($img); $oh = imagesy($img);
        $s = min(1, $w / max($ow, $oh));
        $nw = (int)round($ow * $s); $nh = (int)round($oh * $s);
        $c = imagecreatetruecolor($nw, $nh);
        imagealphablending($c, false); imagesavealpha($c, true);
        imagecopyresampled($c, $img, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
        $out($c, $d . '/' . $n);
    }
    return $dir . '/' . $name;
}
function delete_image(?string $p): void {
    if (!$p) return;
    foreach ([$p, thumb_path($p)] as $x) { $f = ROOT . '/uploads/' . $x; if (is_file($f)) @unlink($f); }
}

/* ---------- Vues ---------- */
function view(string $v, array $d = [], string $layout = 'layout'): void {
    extract($d, EXTR_SKIP);
    ob_start(); require ROOT . "/views/$v.php"; $content = ob_get_clean();
    require ROOT . "/views/$layout.php";
}
function partial(string $v, array $d = []): void { extract($d, EXTR_SKIP); require ROOT . "/views/partials/$v.php"; }
function not_found(): never { http_response_code(404); view('404', ['title' => '404']); exit; }
