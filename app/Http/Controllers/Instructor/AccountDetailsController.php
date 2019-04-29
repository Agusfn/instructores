<?php

namespace App\Http\Controllers\Instructor;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class AccountDetailsController extends Controller
{


	public function __construct()
	{
		$this->middleware("auth:instructor");
	}
    

	public function index()
	{
		return view("instructor.account")->with("instructor", Auth::user());
	}



	public function sendVerifyInfo(Request $request)
	{


		$validator = Validator::make($request->all(), [
            'phone_number' => 'required'
        ]);

		if ($validator->fails()) {
		            return redirect()->back()
		                        ->withErrors($validator)
		                        ->withInput();
		        }

		$request->validate([
		    'identification' => 'required|image|max:5120',
		    'certificate' => 'required|image|max:5120',
		    'phone_number' => 'required',
		]);

		
	}



}
