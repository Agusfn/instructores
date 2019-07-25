<?php

namespace App\Http\Controllers\Instructor\Auth;

use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VerificationController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user,instructor');
        $this->middleware('signed')->only('verify'); // signed request
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    /**
     * Mark the authenticated instructor's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {	

    	$instructor = Instructor::findByEmailOrFail($request->route("email"));

        if(!$instructor->hasVerifiedEmail()) {
			$instructor->markEmailAsVerified();
        }

        return redirect()->route("instructor.login")->with('verified', true);
    }


    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }*/


}
