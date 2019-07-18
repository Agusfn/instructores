<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\InstructorMpAccount;
use App\InstructorCollection;
use App\InstructorBankAccount;
use Illuminate\Support\Facades\Mail;
use App\Lib\AdminEmailNotifications;
use App\Http\Validators\Instructor\RequestCollection;
use App\Mail\Admin\Collections\CollectionRequestCreated;
use App\Mail\Instructor\Collections\CollectionAccountChanged;

class AccountBalanceController extends InstructorPanelBaseController
{
	
	public function __construct()
	{
		parent::__construct();
		$this->middleware("instructor.approved")->except("overview");
	}


	/**
	 * Display account balance page.
	 * @return [type] [description]
	 */
	public function overview()
	{
		if($this->instructor->isApproved())
			$walletMovements = $this->instructor->wallet->movements()->orderBy("id", "DESC")->paginate(10);
		else
			$walletMovements = null;

		return view("instructor.panel.balance.overview")->with([
			"wallet" => $this->instructor->wallet, // null if not approved
			"walletMovements" => $walletMovements
		]);
	}


	/**
	 * Show the form to set up for the first time or edit an existent instructor bank account.
	 */
	public function showBankAccountForm()
	{
		return view("instructor.panel.balance.bank-account")->with([
			"bankAccount" => $this->instructor->bankAccount, // null if not configured by instructor
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

		if(!$this->instructor->bankAccount) {
			InstructorBankAccount::create([
				"instructor_id" => $this->instructor->id,
				"cbu" => $request->cbu,
				"holder_name" => $request->holder_name,
				"document_number" => $request->document_number,
				"cuil_cuit" => $request->cuil_cuit
			]);
			$this->instructor->load("bankAccount");
		}
		else {

			if($this->instructor->bankAccount->hasPendingCollections() > 0) {
				return redirect()->route("instructor.balance.bank-account");
			}

			$this->instructor->bankAccount->fill($request->only([
				"cbu",
				"holder_name",
				"document_number",
				"cuil_cuit"
			]));
			$this->instructor->bankAccount->save();
		}

		Mail::to($this->instructor)->send(new CollectionAccountChanged($this->instructor, "bank"));

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

		if(!$this->instructor->mpAccount) {
			InstructorMpAccount::create([
				"instructor_id" => $this->instructor->id,
				"email" => $request->email
			]);
			$this->instructor->load("mpAccount");
		}
		else {

			if($this->instructor->mpAccount->hasPendingCollections()) {
				return redirect()->back()->withErrors("La cuenta de MercadoPago tiene solicitudes de extracciones de dinero pendientes, no es posible modificar.");
			}
			else if($this->instructor->mpAccount->email == $request->email) {
				return redirect()->back()->withErrors("Ingresa una e-mail de cuenta diferente al anterior.");
			}

			$this->instructor->mpAccount->email = $request->email;
			$this->instructor->mpAccount->save();
		}

		Mail::to($this->instructor)->send(new CollectionAccountChanged($this->instructor, "mercadopago"));

		return redirect()->route("instructor.balance.overview");
	}


	/**
	 * Show form for collection.
	 * @return [type] [description]
	 */
	public function showCollectionForm()
	{
		return view("instructor.panel.balance.withdraw");
	}


	/**
	 * Make a request for collection/withdrawment of money (ARS) from an instructor wallet to their bank account.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function createCollectionRequest(Request $request)
	{
		if(!$this->instructor->wallet) {
			return redirect()->route("instructor.balance.withdraw");
		}

		$validator = new RequestCollection($request);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}


		if($request->destination == "bank") {
			if(!$this->instructor->bankAccount || !$this->instructor->bankAccount->lockTimePassed())
				return redirect()->route("instructor.balance.withdraw");
		}
		else {
			if(!$this->instructor->mpAccount || !$this->instructor->mpAccount->lockTimePassed())
				return redirect()->route("instructor.balance.withdraw");
		}


		$collectionData = [
			"instructor_wallet_id" => $this->instructor->wallet->id,
			"status" => InstructorCollection::STATUS_PENDING,
			"amount" => $request->amount,
			"destination_acc_type" => $request->destination == "bank" ? InstructorCollection::DESTINATION_BANK : InstructorCollection::DESTINATION_MP
		];
		
		if($request->destination == "bank")
			$collectionData["instructor_bank_acc_id"] = $this->instructor->bankAccount->id;
		else 
			$collectionData["instructor_mp_acc_id"] = $this->instructor->mpAccount->id;
		

		$collection = InstructorCollection::create($collectionData);


		Mail::to(AdminEmailNotifications::recipients())->send(new CollectionRequestCreated($this->instructor, $collection));

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

		$collection = $this->instructor->wallet->collections()
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
