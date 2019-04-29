<?php

namespace App;

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



    public static function findByEmail($email) 
    {
        return self::where("email", $email)->first();
    }


    public function sendWelcomeAndVerificationEmail()
    {
        return Mail::to($this)->send(new UserWelcomeEmail($this));
    }



    public function isApproved()
    {
        return (bool)$this->approved;
    }


    public function approvalDocsSent()
    {
        if($this->identification_imgs == null || $this->professional_cert_imgs == null)
            return false;
        else
            return true;
    }


    public function approve()
    {
        $this->approved = true;
        $this->approved_at = date("Y-m-d H:i:s");
        $this->save();
    }


}
