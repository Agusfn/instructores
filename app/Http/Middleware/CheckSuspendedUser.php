<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSuspendedUser
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
        $user = Auth::user();

        if($user instanceof \App\User || $user instanceof \App\Instructor) {

            if($user->suspended) {
                Auth::logout();
                return redirect()->route("home");
            }
        }

        return $next($request);
    }
}
