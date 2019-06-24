<?php

namespace App;

use App\Lib\MercadoPago;
use Illuminate\Database\Eloquent\Model;

class MercadopagoPayment extends Model
{
    
	protected $guarded = [];

	public $timestamps = false;
	

	/**
	 * Find a MercadopagoPayment by its mercadopago payment id
	 * @param  int $mpId
	 * @return App\MercadopagoPayment|null
	 */
	public static function findByMpId($mpId)
	{
		return self::where("mp_payment_id", $mpId)->first();
	}



	public function reservationPayment()
	{
		return $this->belongsTo("App\ReservationPayment");
	}


	/**
	 * Refund the mercadopago payment and mark this entity as refunded.
	 * @return boolean
	 */
	public function refund()
	{
		if($this->status != "approved")
			return false;

		if(!MercadoPago::refundPayment($this->mp_payment_id))
			return false;

		$this->fill([
			"status" => "refunded",
			"status_detail" => "refunded",
			"date_updated" => date("Y-m-d H:i:s")
		]);
		$this->save();

		return true;
	}


	/**
	 * Cancel this mercadopago Payment.
	 * @return [type] [description]
	 */
	public function cancel()
	{
		if($this->status != "pending" && $this->status != "in_process")
			return false;

		if(!MercadoPago::cancelPayment($this->mp_payment_id))
			return false;

		$this->fill([
			"status" => "cancelled",
			"status_detail" => "by_collector",
			"date_updated" => date("Y-m-d H:i:s")
		]);
		$this->save();

		return true;
	}



	/**
	 * Check whether this payment is done with a credit card.
	 * @return boolean [description]
	 */
	public function isWithCreditCard()
	{
		return ($this->payment_type_id != "ticket" && $this->payment_type_id != "atm");
	}




}
