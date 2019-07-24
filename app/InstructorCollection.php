<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class InstructorCollection extends Model
{
   
    use Filterable;

	const STATUS_PENDING = "pending"; // can be canceled.
    //const STATUS_IN_PROCESS = "in_process"; // can't be canceled now.
	const STATUS_COMPLETED = "completed"; // Done.
	const STATUS_REJECTED = "rejected"; // Rejected by admin
    const STATUS_CANCELED = "canceled"; // Canceled by instructor


    const DESTINATION_BANK = "bank";
    const DESTINATION_MP = "mercadopago";


    protected $guarded = [];


    public function instructorWallet()
    {
    	return $this->belongsTo("App\InstructorWallet");
    }


    public function walletMovement()
    {
    	return $this->hasOne("App\InstructorWalletMovement", "wallet_movement_id");
    }


    /**
     * Scope a query to only include pending or in process collections.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where("status", self::STATUS_PENDING);/*->orWhere("status", self::STATUS_IN_PROCESS);*/
    }


    /**
     * [isToBank description]
     * @return boolean [description]
     */
    public function isToBank()
    {
        return $this->destination_acc_type == self::DESTINATION_BANK;
    }


    /**
     * [isToMercadopago description]
     * @return boolean [description]
     */
    public function isToMercadopago()
    {
        return $this->destination_acc_type == self::DESTINATION_MP;
    }


    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    /*public function isInProcess()
    {
        return $this->status == self::STATUS_IN_PROCESS;
    }*/

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public function isCanceled() 
    {
        return $this->status == self::STATUS_CANCELED;
    }


}
