<?php

namespace App;

use App\InstructorCollection;
use App\InstructorWalletMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class InstructorWallet extends Model
{
    
	protected $guarded = [];



	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}

	public function movements()
	{
		return $this->hasMany("App\InstructorWalletMovement");
	}

	public function collections()
	{
		return $this->hasMany("App\InstructorCollection");
	}


	/**
	 * Obtain the total amount of money of all the pending collections together.
	 * @return [type] [description]
	 */
	public function pendingCollectionTotal()
	{
		$collections = DB::table("instructor_collections")->where([
			["instructor_wallet_id", "=", $this->id],
			["status", "=", InstructorCollection::STATUS_PENDING]
		])->sum("amount");

		return $collections;
	}


	/**
	 * Pays the instructor in balnace funds the corresponding amount of a concluded reservation.
	 * @param int $reservationId
	 * @param float $amount 	positive number
	 * @return  null
	 */
	public function addInstructorPayment($reservationId, $amount)
	{
		$amount = round($amount, 2);

		$movement = InstructorWalletMovement::create([
			"instructor_wallet_id" => $this->id,
			"motive" => InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT,
			"reservation_id" => $reservationId,
			"date" => date("Y-m-d H:i:s"),
			"net_amount" => $amount,
			"new_balance" => ($this->balance + $amount)
		]); 

		$this->balance += $amount;
		$this->save();

		return $movement;
	}


	/**
	 * Deducts the instructor in balance funds corresponding amount of a completed collection.
	 * @param int $collectionId
	 * @param float $amount 	positive number
	 * @return  null
	 */
	public function deductInstructorCollection($collectionId, $amount)
	{
		$amount = -1 * round($amount, 2);

		$movement = InstructorWalletMovement::create([
			"instructor_wallet_id" => $this->id,
			"motive" => InstructorWalletMovement::MOTIVE_COLLECTION,
			"collection_id" => $collectionId,
			"date" => date("Y-m-d H:i:s"),
			"net_amount" => $amount,
			"new_balance" => ($this->balance + $amount)
		]); 

		$this->balance += $amount;
		$this->save();

		return $movement;
	}




}
