<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	public function index()
	{
		return view("admin.settings")->with("settings", \Setting::all());
	}


	public function save(Request $request)
	{
		$request->validate([
			"activity_start_date" => "required|date_format:m-d",
			"activity_end_date" => "required|date_format:m-d",
			"service_fee_percentage" => "required|numeric|between:0,100"
		]);

		\Setting::set("reservations.activity_start_date", $request->activity_start_date);
		\Setting::set("reservations.activity_end_date", $request->activity_end_date);
		\Setting::set("prices.service_fee", $request->service_fee_percentage);
		\Setting::save();

		return redirect()->route("admin.settings");
	}

}
