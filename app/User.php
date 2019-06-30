<?php

namespace App;

use App\Mail\UserWelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Lib\Traits\HasProfilePicture;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
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
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id'
    ];


    public function reservations()
    {
        return $this->hasMany("App\Reservation");
    }


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


    public static function findByEmail($email) 
    {
        return self::where("email", $email)->first();
    }


    /*public function sendWelcomeAndVerificationEmail()
    {
        return Mail::to($this)->send(new UserWelcomeEmail($this));
    }*/





}
