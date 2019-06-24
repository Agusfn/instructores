<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\InstructorCollection;
use App\InstructorBankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Lib\AdminEmailNotifications;
use App\Mail\Instructor\BankAccountChanged;
use App\Http\Validators\Instructor\RequestCollection;
use App\Mail\Admin\Collections\CollectionRequestCreated;

class AccountBalanceController extends Controller
{
	
	public function __construct()
	{
		$this->middleware("auth:instructor")->only("overview");
		$this->middleware("instructor.approved")->except("overview");
	}


	/**
	 * Display account balance page.
	 * @return [type] [description]
	 */
	public function overview()
	{
		$instructor = Auth::user();
		$instructor->load("bankAccount", "wallet");

		$walletMovements = $instructor->wallet->movements()->orderBy("date", "DESC")->paginate(10);

		return view("instructor.balance.overview")->with([
			"instructor" => $instructor,
			"bankAccount" => $instructor->bankAccount, // null if not configured by instructor
			"wallet" => $instructor->wallet,
			"walletMovements" => $walletMovements
		]);
	}


	/**
	 * Show the form to set up for the first time or edit an existent instructor bank account.
	 */
	public function showBankAccountForm()
	{
		$instructor = Auth::user();
		$instructor->load("bankAccount", "wallet");

		return view("instructor.balance.bank-account")->with([
			"instructor" => $instructor,
			"bankAccount" => $instructor->bankAccount, // null if not configured by instructor
		]);
	}


	/**
	 * Create a new instructor bank account or update the existing one.
	 * @return [type] [description]
	 */
	public function saveBankAccount(Request $request)
	{
		$request->validate([
			"cbu" => "required|size:22|regex:/^\d{22}$/",
			"holder_name" => "required|between:3,70",
			"document_number" => "required|between:5,30",
			"cuil_cuit" => "required|between:8,20"
		]);
		
		$instructor = Auth::user();

		if($instructor->wallet->collections()->pending()->count() > 0) {
			return redirect()->route("instructor.balance.bank-account");
		}


		if(!$instructor->bankAccount) {
			InstructorBankAccount::create([
				"instructor_id" => $instructor->id,
				"cbu" => $request->cbu,
				"holder_name" => $request->holder_name,
				"document_number" => $request->document_number,
				"cuil_cuit" => $request->cuil_cuit
			]);
		}
		else {
			$instructor->bankAccount->fill($request->only([
				"cbu",
				"holder_name",
				"document_number",
				"cuil_cuit"
			]));
			$instructor->bankAccount->save();
		}


		Mail::to($instructor)->send(new BankAccountChanged($instructor));




		return redirect()->route("instructor.balance.overview");
	}



	/**
	 * Make a request for collection/withdrawment of money (ARS) from an instructor wallet to their bank account.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function createCollectionRequest(Request $request)
	{
		$instructor = Auth::user();

		if(!$instructor->bankAccount || !$instructor->wallet) {
			return redirect()->route("instructor.balance.overview");
		}

		$validator = new RequestCollection($request);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator, 'collection')->withInput();
		}


		$collection = InstructorCollection::create([
			"instructor_wallet_id" => $instructor->wallet->id,
			"status" => InstructorCollection::STATUS_PENDING,
			"amount" => $request->amount
		]);


		Mail::to(AdminEmailNotifications::recipients())->send(new CollectionRequestCreated($instructor, $collection));

		return redirect()->back();
	}


	/**
	 * Cancel an instructor's pending collection.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function cancelCollection(Request $request)
	{
		$request->validate([
			"collection_id" => "required|integer"
		]);

		$instructor = Auth::user();

		$collection = $instructor->wallet->collections()
			->where([
				["id", "=", $request->collection_id],
				["status", "=", InstructorCollection::STATUS_PENDING]
			])->first();

		if($collection) {
			$collection->status = InstructorCollection::STATUS_CANCELED;
			$collection->save();
		}

		return redirect()->back();
	}


}
