<?php

namespace App\Http\Controllers;

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
        //dd(\Auth::guard("admin")->user());
        return view('home');
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
