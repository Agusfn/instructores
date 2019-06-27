<?php

namespace App\Http\Controllers\Instructor\Auth;

use App\User;
use Socialite;
use App\Instructor;
use App\Lib\Helpers\Strings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SocialLoginController extends Controller
{
	

    /**
     * Redirect instructors after logging in (if no intended redirection)
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
        return view('instructor.auth.login');
    }


	/**
	 * Redirects the user (instructor) to the respective social login page.
	 * @param  string $provider    social network provider to log in
	 * @return [type]
	 */
    public function redirectToSocialLogin($provider)
    {
        try {
            return Socialite::with($provider)->redirectUrl(config("services.".$provider.".redirect_instructor"))->redirect();
        } 
        catch (\InvalidArgumentException $e) {
            return redirect()->route('instructor.login');
        }
    }


    /**
     * Handle the social login provider response and log the user in (and register it if not registered)
     * @param  [type] $provider [description]
     * @return [type]           [description]
     */
    public function getSocialCallback(Request $request, $provider)
    {
    	try {
    		$socialUser = Socialite::with($provider)->redirectUrl(config("services.".$provider.".redirect_instructor"))->user();
    	}
    	catch(\Exception $e) {
    		return redirect()->back()->withErrors("Ocurrió un error intentando iniciar sesión, intentalo nuevamente.");
    	}


    	$instructor = Instructor::findByProviderNameAndId($provider, $socialUser->id);

    	if(!$instructor) {

    		if(User::findByProviderNameAndId($provider, $socialUser->id)) {
    			return redirect()->back()->withErrors("Ya existe una cuenta de usuario (no instructor) registrada con esta cuenta de ".$provider.".");
    		}

            $instructor = SocialLogin::createInstructor($socialUser, $provider);
    	}

        if($instructor->suspended) {
            return redirect()->back()->withErrors("La cuenta de instructor está suspendida.");
        }


    	Auth::guard("instructor")->login($instructor);

    	return redirect()->intended($this->redirectTo);
    }



    /**
     * Log instructor out.
     * @return [type] [description]
     */
    public function logout()
    {
    	Auth::guard("instructor")->logout();

    	return redirect()->route("home");
    }

}
