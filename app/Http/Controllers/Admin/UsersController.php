<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	public function list()
	{
		$users = User::orderBy("created_at", "DESC")->paginate(15);
		return view("admin.users.list")->with("users", $users);
	}



	/**
	 * Display user details page. **** PAGINATE RESERVATIONS ******
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function details($id)
	{
		$user = User::find($id);

		if(!$user)
			return redirect()->route("admin.users.list");

		$reservations = $user->reservations()->with("instructor:id,name,surname")->orderBy("id", "DESC")->get();

		return view("admin.users.details")->with([
			"user" => $user,
			"reservations" => $reservations
		]);
	}



	/**
	 * Suspender/habilitar usuario.
	 * @return [type] [description]
	 */
	public function toggleSuspend($id)
	{
		$user = User::find($id);

		if(!$user)
			return redirect()->route("admin.users.list");


		$user->suspended = $user->suspended ? false : true;
		$user->save();

		return redirect()->back();
	}


}
