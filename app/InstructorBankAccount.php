<?php

namespace App;

use App\Lib\InstructorCollections\WithdrawableAccount;

class InstructorBankAccount extends WithdrawableAccount
{

	protected $guarded = [];


	/**
	 * Get the collection requests created to be sent to this bank account.
	 * @return [type] [description]
	 */
	public function collections()
	{
		return $this->hasMany("App\InstructorCollection", "instructor_bank_acc_id");
	}


}
