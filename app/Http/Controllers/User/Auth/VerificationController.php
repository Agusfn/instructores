<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    
	public function verify(Request $request)
	{
		dump($request->email);
		dump($request);
		dd($request->hasValidSignature());
	}

}
