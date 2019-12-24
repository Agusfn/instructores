<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorReview extends Model
{
    	
    /**
     * Attributed that should be guarded.
     * @var array
     */
    protected $guarded = [];



    /**
     * Return the related user who made this review.
     * @return App\User|null
     */
    public function user()
    {
    	return $this->belongsTo("App\User");
    }


    /**
     * Return the related instructor to which this review was made.
     * @return App\Instructor|null
     */
    public function instructor()
    {
    	return $this->belongsTo("App\Instructor");
    }





}
