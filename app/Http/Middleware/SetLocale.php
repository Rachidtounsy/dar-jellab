<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $loc = $request->cookie('lang');
        app()->setLocale(in_array($loc, ['ar', 'fr'], true) ? $loc : 'ar');

        return $next($request);
    }
}
