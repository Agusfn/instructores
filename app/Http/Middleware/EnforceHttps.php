<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class EnforceHttps
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
        if (!$request->secure() && config("app.url") == "https://instructores.com.ar") {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request); 
    }
}
