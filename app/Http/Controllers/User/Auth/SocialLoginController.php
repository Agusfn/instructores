<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Socialite;
use App\Instructor;
use App\Lib\SocialLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SocialLoginController extends Controller
{


    /**
     * Redirect users after logging in (if no intended redirection)
     * @var string
     */
    public $redirectTo = "/";



	public function __construct()
	{
		$this->middleware('guest:user,instructor')->except('logout');
	}



    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }



	/**
	 * Redirects the use to the proper social login page.
	 * @param  string $provider
	 * @return [type]
	 */
    public function redirectToSocialLogin($provider)
    {
        try {
            return Socialite::with($provider)->redirect();
        } 
        catch (\InvalidArgumentException $e) {
            return redirect()->route('user.login');
        }
    }


    /**
     * Handle the social login provider response and log the user in (and register it if not registered)
     * @param  string $provider
     * @return [type]
     */
    public function getSocialCallback(Request $request, $provider)
    {
    	try {
    		$socialUser = Socialite::with($provider)->user();
    	}
    	catch(\Exception $e) {
    		return redirect()->route('user.login')->withErrors("Ocurrió un error intentando iniciar sesión, intentalo nuevamente.");
    	}


    	$user = User::findByProviderNameAndId($provider, $socialUser->id);

    	if(!$user) {
            
    		if(Instructor::findByProviderNameAndId($provider, $socialUser->id)) {
    			return redirect()->route("user.login")->withErrors("Ya existe una cuenta de instructor registrada con esta cuenta de ".$provider.".");
    		}

            $user = SocialLogin::createUser($socialUser, $provider);
    	}

    	Auth::guard("user")->login($user);


    	return redirect()->intended($this->redirectTo);
    }


    /**
     * Log user out.
     * @return [type] [description]
     */
    public function logout()
    {
    	Auth::guard("user")->logout();

    	return redirect()->route("home");
    }



}
