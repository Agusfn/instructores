<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\InstructorMpAccount;
use App\InstructorCollection;
use App\InstructorBankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Lib\AdminEmailNotifications;
use App\Http\Validators\Instructor\RequestCollection;
use App\Mail\Admin\Collections\CollectionRequestCreated;
use App\Mail\Instructor\Collections\CollectionAccountChanged;

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

		if($instructor->isApproved())
			$walletMovements = $instructor->wallet->movements()->orderBy("date", "DESC")->paginate(10);
		else
			$walletMovements = null;

		return view("instructor.balance.overview")->with([
			"instructor" => $instructor,
			"wallet" => $instructor->wallet, // null if not approved
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


		if(!$instructor->bankAccount) {
			InstructorBankAccount::create([
				"instructor_id" => $instructor->id,
				"cbu" => $request->cbu,
				"holder_name" => $request->holder_name,
				"document_number" => $request->document_number,
				"cuil_cuit" => $request->cuil_cuit
			]);
			$instructor->load("bankAccount");
		}
		else {

			if($instructor->bankAccount->hasPendingCollections() > 0) {
				return redirect()->route("instructor.balance.bank-account");
			}

			$instructor->bankAccount->fill($request->only([
				"cbu",
				"holder_name",
				"document_number",
				"cuil_cuit"
			]));
			$instructor->bankAccount->save();
		}

		Mail::to($instructor)->send(new CollectionAccountChanged($instructor, "bank"));

		return redirect()->route("instructor.balance.overview");
	}



	/**
	 * Update or create an instructor mercadopago account.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function updateMpAccount(Request $request)
	{
		$request->validate([
			"email" => "required|email"
		]);

		$instructor = Auth::user();


		if(!$instructor->mpAccount) {
			InstructorMpAccount::create([
				"instructor_id" => $instructor->id,
				"email" => $request->email
			]);
			$instructor->load("mpAccount");
		}
		else {

			if($instructor->mpAccount->hasPendingCollections()) {
				return redirect()->back()->withErrors("La cuenta de MercadoPago tiene solicitudes de extracciones de dinero pendientes, no es posible modificar.");
			}
			else if($instructor->mpAccount->email == $request->email) {
				return redirect()->back()->withErrors("Ingresa una e-mail de cuenta diferente al anterior.");
			}

			$instructor->mpAccount->email = $request->email;
			$instructor->mpAccount->save();
		}

		Mail::to($instructor)->send(new CollectionAccountChanged($instructor, "mercadopago"));

		return redirect()->route("instructor.balance.overview");
	}


	/**
	 * Show form for collection.
	 * @return [type] [description]
	 */
	public function showCollectionForm()
	{
		$instructor = Auth::user();
		return view("instructor.balance.withdraw")->with("instructor", $instructor);
	}


	/**
	 * Make a request for collection/withdrawment of money (ARS) from an instructor wallet to their bank account.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function createCollectionRequest(Request $request)
	{
		$instructor = Auth::user();

		if(!$instructor->wallet) {
			return redirect()->route("instructor.balance.withdraw");
		}

		$validator = new RequestCollection($request);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}


		if($request->destination == "bank") {
			if(!$instructor->bankAccount || !$instructor->bankAccount->lockTimePassed())
				return redirect()->route("instructor.balance.withdraw");
		}
		else {
			if(!$instructor->mpAccount || !$instructor->mpAccount->lockTimePassed())
				return redirect()->route("instructor.balance.withdraw");
		}


		$collectionData = [
			"instructor_wallet_id" => $instructor->wallet->id,
			"status" => InstructorCollection::STATUS_PENDING,
			"amount" => $request->amount,
			"destination_acc_type" => $request->destination == "bank" ? InstructorCollection::DESTINATION_BANK : InstructorCollection::DESTINATION_MP
		];
		
		if($request->destination == "bank")
			$collectionData["instructor_bank_acc_id"] = $instructor->bankAccount->id;
		else 
			$collectionData["instructor_mp_acc_id"] = $instructor->mpAccount->id;
		

		$collection = InstructorCollection::create($collectionData);


		Mail::to(AdminEmailNotifications::recipients())->send(new CollectionRequestCreated($instructor, $collection));

		return redirect()->route("instructor.balance.overview");
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
