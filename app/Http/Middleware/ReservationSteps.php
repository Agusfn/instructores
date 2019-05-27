<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;

class ReservationSteps
{

    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($this->auth->guard("instructor")->check()) {

            if(isset($request->route()->parameters['service_number'])) {
                
                return redirect()->route(
                    "service-page", 
                    $request->route()->parameters['service_number']
                )->withErrors("No se pueden reservar clases siendo instructor.");

            }
            else {
                return redirect()->route("home");
            }
            
        }
        else if(!$this->auth->guard("user")->check()) {

            throw new AuthenticationException(
                'Unauthenticated.', ["user"], route("user.login")
            );

        }


        return $next($request);
    }
}
