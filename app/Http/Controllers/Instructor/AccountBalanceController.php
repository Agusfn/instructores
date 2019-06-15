<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountBalanceController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth:instructor");
	}


	/**
	 * Display account balance page.
	 * @return [type] [description]
	 */
	public function index()
	{
		$instructor = Auth::user();

		return view("instructor.balance.overview")->with([
			"instructor" => $instructor,
			//"balanceMovements" => $instructor->balanceMovements
		]);
	}


	/**
	 * Show the form to set up for the first time or edit an existent instructor bank account.
	 */
	public function showBankAccountForm()
	{
		return view("instructor.balance.bank-account");
	}


}
