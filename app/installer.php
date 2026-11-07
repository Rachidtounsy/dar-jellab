<?php
require_once ROOT . '/database/schema.php';
require_once ROOT . '/database/seed.php';

/** Crée config.php, les tables, les listes de base, l'admin (+ démo). Retourne un message d'erreur ou null. */
function install_run(array $o): ?string {
    $driver = $o['driver'] ?? 'mysql';
    try {
        if ($driver === 'sqlite') {
            $file = $o['sqlite'] ?? ROOT . '/data/dar.sqlite';
            if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
            $pdo = new PDO('sqlite:' . $file);
            $pdo->exec('PRAGMA foreign_keys=ON');
        } else {
            $pdo = new PDO("mysql:host={$o['host']};dbname={$o['name']};charset=utf8mb4", $o['user'], $o['pass']);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        schema_apply($pdo, $driver);
        if ((int)$pdo->query('SELECT COUNT(*) FROM terms')->fetchColumn() === 0) seed_base($pdo);
        if (!empty($o['demo']) && (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn() === 0) seed_demo($pdo);
        if (!filter_var($o['email'] ?? '', FILTER_VALIDATE_EMAIL) || strlen($o['password'] ?? '') < 8) return 'Email invalide ou mot de passe < 8 caractères.';
        $pdo->prepare('REPLACE INTO admins (email, pass) VALUES (?, ?)')->execute([$o['email'], password_hash($o['password'], PASSWORD_DEFAULT)]);
    } catch (Throwable $e) {
        return 'Erreur : ' . $e->getMessage();
    }
    $cfg = ['db_driver' => $driver, 'db_host' => $o['host'] ?? 'localhost', 'db_name' => $o['name'] ?? '', 'db_user' => $o['user'] ?? '', 'db_pass' => $o['pass'] ?? '',
            'db_sqlite' => $o['sqlite'] ?? ROOT . '/data/dar.sqlite', 'base_url' => $o['base_url'] ?? '', 'debug' => false];
    if (file_put_contents(ROOT . '/config.php', "<?php\nreturn " . var_export($cfg, true) . ";\n") === false) return 'Impossible d\'écrire config.php (permissions).';
    return null;
}
