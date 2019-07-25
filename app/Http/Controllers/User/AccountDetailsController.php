<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
		return view("user.panel.account.details")->with("user", $user);
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
		return view("user.panel.account.edit")->with("user", $user);
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
			"phone_number" => "nullable|string|between:5,30|regex:/^[0-9+ -]*$/"
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


	/**
	 * Show change password form.
	 * @return [type] [description]
	 */
	public function showPasswordForm()
	{
		$user = Auth::user();

		if($user->hasSocialLogin())
			return redirect()->route("user.account");

		return view("user.panel.account.change-password");
	}


	/**
	 * Process change password.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function changePassword(Request $request)
	{
        $request->validate([
            "current_password" => "required",
            "new_password" => "required|min:6|max:100|confirmed"
        ]);

        $user = Auth::user();

		if($user->hasSocialLogin())
			return redirect()->route("user.account");

        if(!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(["current_password" => "La contraseña actual ingresada es inválida."]);
        }

        if(Hash::check($request->new_password, $user->password)) {
            return redirect()->back()->withErrors(["new_password" => "La nueva contraseña no puede ser igual a la actual."]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();


        request()->session()->flash('success');
        return redirect()->back();
	}


}
