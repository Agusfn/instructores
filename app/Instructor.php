<?php

namespace App;

use App\Reservation;
use App\InstructorWallet;
use App\Lib\Reservations;
use App\InstructorService;
use App\Filters\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Instructor\WelcomeEmail;
use App\Lib\Traits\HasProfilePicture;
use App\Mail\Instructor\ResetPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class Instructor extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use Notifiable, HasProfilePicture, Filterable;


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
        "level",
        "review_stars_score"

    ];


    /**
     * Attributes that will be mutated to carbon dates.
     * @var array
     */
    protected $dates = [
        "documents_sent_at",
        "approved_at",
        "last_docs_reject_at"
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


    /**
     * Find a user by email.
     * @param  [type] $email [description]
     * @return [type]        [description]
     */
    public static function findByEmailOrFail($email) 
    {
        $user = self::where("email", $email)->first();
        if(!$user) {
            throw (new ModelNotFoundException)->setModel(self::class);
        }
        return $user;
    }



    /**
     * Get the service provided by the instructor
     * @return App\InstructorService|null
     */
    public function service()
    {
        return $this->hasOne("App\InstructorService");
    }


    /**
     * Return the associated InstructorMpAccount of this instructor.
     * @return App\InstructorMpAccount|null
     */
    public function mpAccount()
    {
        return $this->hasOne("App\InstructorMpAccount");
    }

    /**
     * Return the associated InstructorBankAccount of this instructor.
     * @return App\InstructorBankAccount|null
     */
    public function bankAccount()
    {
        return $this->hasOne("App\InstructorBankAccount");
    }

    /**
     * Return the associated InstructorWallet of this instructor.
     * @return App\InstructorWallet|null
     */
    public function wallet()
    {
        return $this->hasOne("App\InstructorWallet");
    }


    /**
     * Return the reservations made to this instructor.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function reservations()
    {
        return $this->hasMany("App\Reservation");
    }


    /**
     * Return the reviews made to this instructor.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function reviews()
    {
        return $this->hasMany("App\InstructorReview");
    }


    /**
     * If the user was registered and logs in through social media login services.
     * @return boolean
     */
    public function hasSocialLogin()
    {
        return $this->provider != null;
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
     * @param string $reason
     * @return null
     */
    public function rejectDocs($reason)
    {
        $this->documents_sent_at = null;
        $this->identification_imgs = null;
        $this->professional_cert_imgs = null;

        $this->last_docs_reject_at = date("Y-m-d H:i:s");
        $this->last_docs_reject_reason = $reason;

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



    /**
     * Get the total amount of the instructor pay of all the confirmed reservations of the instructor.
     * @return float
     */
    public function getPendingAmountToBeAccredited()
    {
        return $this->reservations()->where("status", Reservation::STATUS_CONFIRMED)->sum("instructor_pay");
    }



    /**
     * Send the welcome and verification (if normal login) email.
     *
     * @return void
     */
    public function sendWelcomeAndVerificationEmail()
    {
        Mail::to($this)->send(new WelcomeEmail($this));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this)->send(new ResetPassword($this, $token));
    }



    /**
     * Return an array of the count and percentage of each of the 1-5 stars ratings.
     * @return array
     */
    public function getGroupedReviewScore()
    {
        $ratings = DB::table("instructor_reviews")
            ->selectRaw("COUNT(*) as count, rating_stars")
            ->where("instructor_id", $this->id)
            ->groupBy("rating_stars")
            ->get()->toArray();

        $totalRatings = 0;

        foreach($ratings as $rating) {
            $allRatings[$rating->rating_stars]["count"] = $rating->count;
            $totalRatings += $rating->count;
        }
        
        for($i = 5; $i > 0; $i--) 
        {
            if(!isset($allRatings[$i])) {
                $allRatings[$i]["count"] = 0;
                $allRatings[$i]["percentage"] = 0;
            }
            else {
                $allRatings[$i]["percentage"] = round($allRatings[$i]["count"]/$totalRatings * 100);
            }
        }
        
        krsort($allRatings);
        return $allRatings;
    }



    /**
     * Calculate and set the average review score from all the reviews.
     * @return null
     */
    public function calculateReviewScore()
    {
        $this->load("reviews");
        $reviews = $this->reviews;

        $sum = 0;
        $count = 0;

        foreach($reviews as $review) {
            $sum += $review->rating_stars;
            $count++;
        }

        if($count > 0)
            $this->review_stars_score = round($sum / $count, 1);
        else
            $this->review_stars_score = null;


        $this->save();
    }




}
