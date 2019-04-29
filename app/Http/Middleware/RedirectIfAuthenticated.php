<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  $guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {

        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectTo($guard);
            }
        }

        return $next($request);
    }


    /**
     * [redirectTo description]
     * @param  [type] $guard [description]
     * @return [type]        [description]
     */
    private function redirectTo($guard)
    {
        if($guard == "admin") {
            return redirect()->route("admin.home");
        }
        else if($guard == "user" || $guard == "instructor") {
            return redirect()->route("home");
        }
    }

}
