<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class ApprovedInstructor
{


    /**
     * Redirect guest to instructor login. Redirect unapproved instructors to their panel (account pg).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard("instructor")->check()) {

            if(Auth::guard("instructor")->user()->isApproved())  {
                Auth::shouldUse("instructor");
                return $next($request);
            }
            else {
                $this->redirectUnauthenticated($request, "instructor.account");
            }
        }
        else {
            $this->redirectUnauthenticated($request, "instructor.login");
        }
        
    }

    
    /**
     * Redirect to route with authentication exception.
     * @param  [type] $request [description]
     * @param int $routeName
     * @return [type]            [description]
     */
    private function redirectUnauthenticated($request, $routeName)
    {
        throw new AuthenticationException(
            'Unauthenticated.', ["instructor"], !$request->expectsJson() ? route($routeName) : null
        );
    }


}
