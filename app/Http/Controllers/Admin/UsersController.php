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
		$users = User::all();
		return view("admin.users.list")->with("users", $users);
	}


	public function details($id)
	{
		$user = User::find($id);
		return view("admin.users.details")->with("user", $user);
	}


}
