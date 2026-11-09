<?php

return [

    /*
    | Compte admin créé par `php artisan db:seed` (changez-les dans .env AVANT le premier seed en production).
    */
    'admin_email' => env('ADMIN_EMAIL', 'admin@darjellaba.ma'),
    'admin_password' => env('ADMIN_PASSWORD', 'ChangeMe-2026!'),

    /*
    | Produits de démonstration : toujours en local, ou en production avec SEED_DEMO=true.
    */
    'seed_demo' => (bool) env('SEED_DEMO', false),

];
