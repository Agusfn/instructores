<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class SetGuard
{
    

    /**
     * If user is logged in as User or Instructor, set the corresponding guard, if not, do nothing (remains as "user").
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth::guard("user")->check()) {
            Auth::shouldUse("user");
        }
        else if(Auth::guard("instructor")->check()) {
            Auth::shouldUse("instructor");
        }

        return $next($request);
    }
}
