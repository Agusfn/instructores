<?php

namespace App\Http\Controllers\Instructor;


use Carbon\Carbon;
use App\Lib\MercadoPago;
use App\InstructorMpAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class PaymentsController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth:instructor");
	}

	public function index()
	{
		$instructor = Auth::user();
		return view("instructor.payments")->with([
			"instructor" => $instructor,
			"balanceMovements" => $instructor->balanceMovements,
			"mpAccount" => $instructor->mpAccount
		]);
	}


	/**
	 * Redirects the instructor to the mercadopago association URL (if they don't have a MP account associated)
	 * @return [type] [description]
	 */
	public function redirectToMpAssocUrl()
	{
		$instructor = Auth::user();

		if($instructor->mpAccount != null && $instructor->mpAccount->access_token != null)
			return redirect()->route("instructor.payments");

		
		if($instructor->mpAccount == null) {
			$mpAccount = InstructorMpAccount::create([
				"instructor_id" => $instructor->id,
				"random_id" => \Str::random(),
			]);
		} else {
			$mpAccount = $instructor->mpAccount;
		}

		$url = MercadoPago::marketplaceAssociationUrl("?rid=".$mpAccount->random_id);

		return redirect()->to($url);
	}



	public function associateMpAccount(Request $request)
	{
		$instructor = Auth::user();

		if(!$request->filled("rid", "code"))
			return redirect()->route("instructor.payments");


		if($instructor->mpAccount == null 
			|| $instructor->mpAccount->random_id != $request->rid 
			|| $instructor->mpAccount->access_token != null)
		{
			return redirect()->route("instructor.payments");
		}

		$response = MercadoPago::processMarketplaceAssociation($request->code, "?rid=".$instructor->mpAccount->random_id);

		if(!$response) {
			return redirect()->route("instructor.payments")->withErrors("El código de asociación de cuenta puede que sea incorrecto o haya expirado, intentalo nuevamente.");
		}

		$instructor->mpAccount->fill([
			"access_token" => $response["body"]["access_token"],
			"public_key" => $response["body"]["public_key"],
			"refresh_token" => $response["body"]["refresh_token"],
			"mp_user_id" => $response["body"]["user_id"],
			"scope" => $response["body"]["scope"],
			"expires_on" => (new Carbon())->addSeconds($response["body"]["expires_in"])->format("Y-m-d H:i:s")
		]);
		$instructor->mpAccount->save();

		$request->session()->flash('mp_assoc_success');

		return redirect()->route("instructor.payments");
	}



}
