<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/shop/{segment?}/{cat?}', 'index')->name('shop');
    Route::get('/accessories', 'accessories')->name('accessories');
    Route::get('/sale', 'sale')->name('sale');
    Route::get('/occasion/{slug}', 'occasion')->name('occasion');
    Route::get('/search', 'search')->name('search');
    Route::get('/p/{slug}', 'product')->name('product');
    Route::get('/ph/{hex}/{kind}', 'placeholder')->name('placeholder')->where('hex', '[0-9a-fA-F]{3,8}');
    Route::get('/lang/{l}', 'lang')->name('lang');
    Route::get('/sitemap.xml', 'sitemap');
});

foreach (['about', 'contact', 'delivery'] as $page) {
    Route::view("/$page", "pages.$page")->name($page);
}
