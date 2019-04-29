<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{


    /**
     * The provided guard names to check
     * @var string[]
     */
    private $guards;



    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->guards = $guards;
        $this->authenticate($request, $guards);

        return $next($request);
    }



    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) 
        {
            if(sizeof($this->guards) == 1) 
            {
                $guard = $this->guards[0];

                if($guard == null || $guard == "user") {
                    return route("user.login");
                }
                else if($guard == "instructor") {
                    return route("instructor.login");
                }
                else if($guard == "admin") {
                    return route("admin.login");
                }

            }
            else
                return route('home');
        }
    }
}
