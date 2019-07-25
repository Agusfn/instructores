<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Socialite;
use App\Instructor;
use App\Lib\SocialLogin;
use Illuminate\Http\Request;
use App\Mail\User\WelcomeEmail;
use App\Mail\Admin\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Lib\AdminEmailNotifications;


class SocialLoginController extends Controller
{


    /**
     * Redirect users after logging in (if no intended redirection)
     * @var string
     */
    public $redirectTo = "panel/cuenta";



	public function __construct()
	{
		$this->middleware('guest:user,instructor')->except('logout');
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
    		return redirect()->route("user.login")->withErrors("Ocurrió un error intentando iniciar sesión, intentalo nuevamente.", "social");
    	}


    	$user = User::findByProviderNameAndId($provider, $socialUser->id);

    	if(!$user) {
            
    		if(Instructor::findByProviderNameAndId($provider, $socialUser->id)) {
    			return redirect()->route("user.login")->withErrors("Ya existe una cuenta de instructor registrada con esta cuenta de ".$provider.".", "social");
    		}

            $user = SocialLogin::createUser($socialUser, $provider);

            Mail::to($user)->send(new WelcomeEmail($user));
            Mail::to(AdminEmailNotifications::recipients())->send(new UserRegistered());
    	}

        if($user->suspended) {
            return redirect()->route("user.login")->withErrors("La cuenta de usuario está suspendida.", "social");
        }

    	Auth::guard("user")->login($user);


    	return redirect()->intended($this->redirectTo);
    }



}
