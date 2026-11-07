<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\ShopController;
use App\Models\Order;
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

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'show')->name('cart');
    Route::post('/cart/add', 'add')->name('cart.add')->middleware('throttle:30,1');
    Route::post('/cart/update', 'update')->name('cart.update');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/checkout', 'place')->name('checkout.place')->middleware('throttle:6,1');
    Route::get('/order/{ref}', 'order')->name('order');
});

Route::controller(CultureController::class)->group(function () {
    Route::get('/culture', 'index')->name('culture');
    Route::get('/culture/{slug}', 'show')->name('culture.show');
});
Route::view('/size-guide', 'pages.size-guide')->name('size-guide');

// Bon de livraison imprimable (réservé aux comptes admin connectés)
Route::get('/admin-print/orders/{order}', fn (Order $order) => view('admin.order-print', ['order' => $order->load('items')]))
    ->middleware('auth')->name('orders.print');
