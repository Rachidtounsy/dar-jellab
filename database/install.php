<?php
// CLI : php database/install.php --sqlite [--demo] --email=admin@x.ma --password=motdepasse [--base=/dar-jellab]
define('ROOT', dirname(__DIR__));
require ROOT . '/app/helpers.php';
require ROOT . '/app/installer.php';
$o = ['driver' => 'mysql'];
foreach (array_slice($argv, 1) as $a) {
    if ($a === '--sqlite') $o['driver'] = 'sqlite';
    elseif ($a === '--demo') $o['demo'] = true;
    elseif (preg_match('/^--(\w+)=(.*)$/', $a, $m)) $o[$m[1]] = $m[2];
}
if (isset($o['base'])) $o['base_url'] = $o['base'];
$err = install_run($o);
echo $err ?? "OK\n";
exit($err ? 1 : 0);
