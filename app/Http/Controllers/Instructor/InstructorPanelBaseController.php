<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class InstructorPanelBaseController extends Controller
{
    

    /**
     * Instructor object that is shared across all actions in all controllers belonging to the instructor panel.
     * @var App\Instructor
     */
	protected $instructor;



	public function __construct()
	{
		$this->middleware("auth:instructor");


		$this->middleware(function ($request, $next) {

            $this->instructor = Auth::user();
            View::share("instructor", $this->instructor);

            return $next($request);
        });



	}


}
