<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $lang = ($request->hasHeader('X-Language')) ? $request->header('X-Language') : 'en';

        app()->setlocale($lang);

        return $next($request);

    }
}
