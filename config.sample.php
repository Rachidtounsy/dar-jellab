<?php
// Copiez ce fichier en config.php (ou utilisez install.php qui le crée pour vous).
return [
    'db_driver' => 'mysql',          // mysql | sqlite
    'db_host'   => 'localhost',
    'db_name'   => '',
    'db_user'   => '',
    'db_pass'   => '',
    'db_sqlite' => __DIR__ . '/data/dar.sqlite',
    'base_url'  => '',               // '' à la racine, '/dar-jellab' si sous-dossier
    'debug'     => false,
];
