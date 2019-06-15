<?php

namespace App;

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

}
