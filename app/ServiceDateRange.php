<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ServiceDateRange extends Model
{
    
    /**
     * Set PK column name.
     * Can't be "id" because of some query problem during search Join where it replaces the instructor service id.
     * @var string
     */
    protected $primaryKey = "range_id";


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        "date_start",
        "date_end"
    ];


	/**
	 * Get the instructor service that owns this date range
	 * @return App\InstructorService
	 */
	public function service()
	{
		return $this->belongsTo("App\InstructorService", "instructor_service_id");
	}



}
