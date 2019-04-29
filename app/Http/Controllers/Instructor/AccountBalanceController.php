<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountBalanceController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth:instructor");
	}

	public function index()
	{
		return view("instructor.balance");
	}

}
