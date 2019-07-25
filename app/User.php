<?php

namespace App;

use App\Filters\Filterable;
use App\Mail\User\WelcomeEmail;
use App\Mail\User\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Lib\Traits\HasProfilePicture;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
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
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id'
    ];



    /**
     * Find user by social network login provider name and its respective id.
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




    public function reservations()
    {
        return $this->hasMany("App\Reservation");
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
}


