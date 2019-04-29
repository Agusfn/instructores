<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;


class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user,instructor');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    /**
     * Mark the user/instructor account with the provided email as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {

        if($request->get("type") == "user") {
            $user = User::findByEmail($request->route("email"));
        }
        else if($request->get("type") == "instructor") {
            $user = Instructor::findByEmail($request->route("email"));
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirect($user);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirect($user, true);
    }



    protected function redirect($user, $verified = false)
    {
        if($user instanceof User) {
            return redirect()->route("user.login")->with("verified", $verified);
        }
        else if($user instanceof Instructor) {
            return redirect()->route("instructor.login")->with("verified", $verified);
        }
    }



}
