<?php

namespace App\Http\Controllers;

use App\Lib\Reservations;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view("home")->with([
            "activityStartDate" => Reservations::getCurrentYearActivityStart(),
            "activityEndDate" => Reservations::getCurrentYearActivityEnd()
        ]);

    }


    public function faq()
    {
        return view("faq");
    }



    public function becomeInstructor()
    {
        return view("become-instructor");
    }


}
