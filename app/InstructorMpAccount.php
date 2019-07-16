<?php

namespace App;

use App\Lib\InstructorCollections\WithdrawableAccount;

class InstructorMpAccount extends WithdrawableAccount
{
    protected $guarded = [];


	/**
	 * Get the collection requests created to be sent to this mercadopago account.
	 * @return [type] [description]
	 */
	public function collections()
	{
		return $this->hasMany("App\InstructorCollection", "instructor_mp_acc_id");
	}


}
