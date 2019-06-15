<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class AccountDetailsController extends Controller
{


	public function __construct()
	{
		$this->middleware("auth:user");
	}


	/**
	 * Show account details page.
	 * @return [type] [description]
	 */
	public function index()
	{
		$user = Auth::user();
		return view("user.account")->with("user", $user);
	}


	/**
	 * Change user profile picture through ajax POST request with file.
	 * @param  Request $request
	 * @return [type]           [description]
	 */
	public function changeProfilePic(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"profile_pic" => "required|file|mimes:jpeg|max:5120"
		]);

		if($validator->fails()) {
			return response($validator->messages()->first(), 422);
		}

		$user = Auth::user();

		$image = Image::make($request->file("profile_pic"));
		$user->setProfilePic($image);

		return response("OK", 200);
	}



	/**
	 * Show account details modification form.
	 * @return [type] [description]
	 */
	public function showEditAccountForm()
	{
		$user = Auth::user();
		return view("user.edit-account")->with("user", $user);
	}


	/**
	 * Update user account changes.
	 * @param  Request $request
	 * @return [type]           [description]
	 */
	public function editAccount(Request $request)
	{
		$request->validate([
			"name" => "required|string|between:3,50",
			"surname" => "required|string|between:3,50",
			"phone_number" => "nullable|string|between:5,20|regex:/^[0-9+ -]*$/"
		]);

		$user = Auth::user();

		$user->fill([
			"name" => $request->name,
			"surname" => $request->surname,
			"phone_number" => $request->phone_number
		]);
		$user->save();

		return redirect()->route("user.account");
	}


}
