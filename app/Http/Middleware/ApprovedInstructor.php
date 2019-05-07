<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class ApprovedInstructor
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
        if (Auth::guard("instructor")->check() && Auth::guard("instructor")->user()->isApproved()) {
            Auth::shouldUse("instructor");
            return $next($request);
        }
        else {
            throw new AuthenticationException(
                'Unauthenticated.', ["instructor"], $this->redirectTo($request)
            );
        }
        
    }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('home');
        }
    }


}
