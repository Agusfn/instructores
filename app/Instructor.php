<?php

namespace App;

use App\Lib\Reservations;
use App\InstructorService;
use App\Mail\UserWelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Instructor extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
     * Identification type codes (for DB) and names.
     * @var string[]
     */
    public static $identification_types = [
        "dni" => "DNI", 
        "passport" => "Pasaporte"
    ];


    /**
     * Get name of identification type.
     * @param  string $code
     * @return string
     */
    public static function idTypeName($code)
    {
        return self::$identification_types[$code];
    }



    public static function findByEmail($email) 
    {
        return self::where("email", $email)->first();
    }



    public function mpAccount()
    {
        return $this->hasOne("App\InstructorMpAccount");
    }



    public function sendWelcomeAndVerificationEmail()
    {
        return Mail::to($this)->send(new UserWelcomeEmail($this));
    }



    /**
     * Check if instructor has been approved.
     * @return boolean
     */
    public function isApproved()
    {
        return (bool)$this->approved;
    }


    /**
     * Check whether the instructor has associated its MercadoPago account.
     * @return boolean
     */
    public function hasMpAccountAssociated()
    {
        if($this->mpAccount != null && $this->mpAccount->access_token != null)
            return true;

        return false;
    }



    /**
     * Check if instructor has sent the documents for their approval.
     * @return bool
     */
    public function approvalDocsSent()
    {
        if($this->identification_imgs == null || $this->professional_cert_imgs == null)
            return false;
        else
            return true;
    }


    /**
     * Approve instructor and create its service
     * @return null
     */
    public function approve()
    {
        $service = new InstructorService(); 

        $service->number = InstructorService::generateNumber();
        $service->instructor_id = $this->id;
        $service->worktime_hour_start = Reservations::DAILY_ACTIVITY_START_TIME;
        $service->worktime_hour_end = Reservations::DAILY_ACTIVITY_END_TIME;
        $service->save();

        $this->approved = true;
        $this->approved_at = date("Y-m-d H:i:s");
        $this->save();
    }


    /**
     * Reject the documents sent for approval. Telephone is left unchanged.
     * @return null
     */
    public function rejectDocs()
    {
        $this->documents_sent_at = null;
        $this->identification_imgs = null;
        $this->professional_cert_imgs = null;
        $this->save();
    }


    /**
     * Get the service provided by the instructor
     * @return App\InstructorService|null
     */
    public function service()
    {
        return $this->hasOne("App\InstructorService");
    }



    public function balanceMovements()
    {
        return $this->hasMany("App\InstructorBalanceMovement");
    }


    public function profilePicUrl()
    {
        return \Storage::url("img/instructors/".$this->profile_picture);
    }

}
