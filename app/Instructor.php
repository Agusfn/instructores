<?php

namespace App;

use App\InstructorWallet;
use App\Lib\Reservations;
use App\InstructorService;
use App\Mail\UserWelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Lib\Traits\HasProfilePicture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Instructor extends Authenticatable
{
    use Notifiable, HasProfilePicture;


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
    /*protected $hidden = [
        'password', 'remember_token',
    ];*/


    /**
     * The attributes that should be visible in arrays. (Only used in search bar)
     *
     * @var array
     */
    protected $visible = [
        "name",
        "profile_picture",
        "level"

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


    /**
     * Find instructor by social network login provider name and its respective id.
     * @param  string $providerName
     * @param  string $providerId 
     * @return App\User|null
     */
    public static function findByProviderNameAndId($providerName, $providerId)
    {
        return self::where([
            ["provider", "=", $providerName],
            ["provider_id", "=", $providerId]
        ])->first();
    }



    public static function findByEmail($email) 
    {
        return self::where("email", $email)->first();
    }



    /**
     * Get the service provided by the instructor
     * @return App\InstructorService|null
     */
    public function service()
    {
        return $this->hasOne("App\InstructorService");
    }



    /*public function mpAccount()
    {
        return $this->hasOne("App\InstructorMpAccount");
    }*/

    public function bankAccount()
    {
        return $this->hasOne("App\InstructorBankAccount");
    }

    public function wallet()
    {
        return $this->hasOne("App\InstructorWallet");
    }


    public function reservations()
    {
        return $this->hasMany("App\Reservation");
    }




    /*public function sendWelcomeAndVerificationEmail()
    {
        return Mail::to($this)->send(new UserWelcomeEmail($this));
    }*/



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
    public function hasBankAccount()
    {
        return $this->bankAccount()->exists();
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
     * Approve an instructor, create their InstructorService and their InstructorWallet.
     * 
     * @param  string $idType   Identification type. Types defined in self::$identification_types
     * @param  string $idNumber
     * @param  int $level    level of instructor.
     * @return null
     */
    public function approve($idType, $idNumber, $level)
    {
        
        InstructorService::create([
            "number" => InstructorService::generateNumber(),
            "instructor_id" => $this->id,
            "worktime_hour_start" => Reservations::DAILY_ACTIVITY_START_TIME,
            "worktime_hour_end" => Reservations::DAILY_ACTIVITY_END_TIME,
        ]);

        InstructorWallet::create([
            "instructor_id" => $this->id,
            "currency_code" => \App\Lib\Currencies::CODE_ARS
        ]);

        $this->fill([
            "approved" => true,
            "approved_at" => date("Y-m-d H:i:s"),
            "identification_type" => $idType,
            "identification_number" => $idNumber,
            "level" => $level
        ]);

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
     * Deletes all certification images (if any) from storage. (Certif. images code may be refactored)
     * @return void
     */
    public function deleteApprovalDocuments()
    {
        Storage::disk("local")->deleteDirectory("instructor_documents/".$this->id);
    }





}
