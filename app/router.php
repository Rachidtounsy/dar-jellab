<?php
$GLOBALS['ROUTES'] = [];
function route(string $m, string $pattern, callable $fn): void {
    $re = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '/?$#u';
    $GLOBALS['ROUTES'][] = [$m, $re, $fn];
}
function dispatch(): void {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $b = base(); if ($b !== '' && str_starts_with($path, $b)) $path = substr($path, strlen($b));
    $path = '/' . trim(rawurldecode($path), '/');
    $m = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'POST' : 'GET';
    foreach ($GLOBALS['ROUTES'] as [$rm, $re, $fn]) {
        if ($rm === $m && preg_match($re, $path, $mm)) { $fn(array_filter($mm, 'is_string', ARRAY_FILTER_USE_KEY)); return; }
    }
    not_found();
}
