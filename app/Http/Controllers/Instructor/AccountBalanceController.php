<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class AccountBalanceController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth:instructor");
	}

	public function index()
	{
		$instructor = Auth::user();
		return view("instructor.balance")->with([
			"instructor" => $instructor,
			"balanceMovements" => $instructor->balanceMovements
		]);
	}

}
