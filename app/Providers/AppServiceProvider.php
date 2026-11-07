<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Term;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Données partagées (mises en cache le temps de la requête)
        $shared = null;
        View::composer(['layouts.app', 'shop.home', 'shop.index'], function ($view) use (&$shared) {
            $shared ??= [
                'navTree' => Category::active()->orderBy('sort')->orderBy('id')->get()->groupBy('segment'),
                'occasions' => Term::ofType('occasion')->get(),
            ];
            $view->with($shared);
        });
    }
}
