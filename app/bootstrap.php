<?php
declare(strict_types=1);
define('ROOT', dirname(__DIR__));
$CFG = is_file(ROOT . '/config.php') ? require ROOT . '/config.php' : null;
require ROOT . '/app/helpers.php';
if (cfg('debug')) { ini_set('display_errors', '1'); error_reporting(E_ALL); }
if (PHP_SAPI !== 'cli') {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
    session_start();
}
require ROOT . '/app/shop.php';
