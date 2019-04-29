<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountDetailsController extends Controller
{

	public function __construct()
	{
		$this->middleware("auth:user");
	}

	public function index()
	{
		return view("user.account");
	}

}
