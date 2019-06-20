<?php

namespace App;

use App\Lib\PaymentMethods;
use Illuminate\Database\Eloquent\Model;

class ReservationPayment extends Model
{
    
	const STATUS_PROCESSING = "processing";
	const STATUS_SUCCESSFUL = "successful";
	const STATUS_FAILED = "failed";
	const STATUS_REFUNDED = "refunded";
	const STATUS_CHARGEBACKED = "chargebacked";

	protected $guarded = [];


	protected $dates = ["paid_at"];



	public function scopeNewestsFirst($query)
	{
		return $query->orderBy("created_at", "DESC");
	}


	public function reservation()
	{
		return $this->belongsTo("App\Reservation");
	}
	

	public function mercadopagoPayment()
	{
		return $this->hasOne("App\MercadopagoPayment");
	}


	public function isProcessing()
	{
		return $this->status == self::STATUS_PROCESSING;
	}

	public function isSuccessful()
	{
		return $this->status == self::STATUS_SUCCESSFUL;
	}

	public function isFailed()
	{
		return $this->status == self::STATUS_FAILED;
	}

	public function isRefunded()
	{
		return $this->status == self::STATUS_REFUNDED;
	}

	public function isChargebacked()
	{
		return $this->status == self::STATUS_CHARGEBACKED;
	}			


	/**
	 * Check if the method of this payment is Mercadopago.
	 * @return boolean
	 */
	public function isMercadoPago()
	{
		return $this->payment_method_code == PaymentMethods::CODE_MERCADOPAGO;
	}


	/**
	 * Refund the payment.
	 * @return boolean
	 */
	public function refund()
	{
		if(!$this->isSuccessful())
			return false;

		if($this->isMercadoPago()) {

			if(!$this->mercadopagoPayment->refund())
				return false;

			$this->status = self::STATUS_REFUNDED;
			$this->save();
			return true;
		}
		else {
			return false;
		}

	}



}
