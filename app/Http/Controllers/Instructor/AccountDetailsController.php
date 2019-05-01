<?php

namespace App\Http\Controllers\Instructor;

use \Hash;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AccountDetailsController extends Controller
{


	public function __construct()
	{
		$this->middleware("auth:instructor");
	}
    

	public function index()
	{
		return view("instructor.account")->with("instructor", Auth::user());
	}



	public function showChangePasswordForm()
	{
		return view("instructor.change-password");
	}


	public function changePassword(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
			"password" => "required|string|min:8|confirmed"
		]);

		$validator->after(function ($validator) {
		    if(!Hash::check(request()->input("current_password"), Auth::user()->password)) {
		        $validator->errors()->add('current_password', 'La contraseña ingresada es inválida.');
		    }
		});

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Auth::user()->password = Hash::make($request->input("password"));
        Auth::user()->save();

        $request->session()->flash('success');
        return redirect()->back();

	}



	public function sendVerifyInfo(Request $request)
	{

		$messages = [
			"identification_imgs.max" => "No se pueden subir más de :max imágenes.",
			"certificate_imgs.size" => "Debés subir :size imágenes, una de cada cara."
		];

		Validator::make($request->all(), [
            'phone_number' => 'required',
            'identification_imgs' => 'required|max:2',
            'identification_imgs.*' => 'required|mimes:png,jpeg,bmp|max:5120',
            'certificate_imgs' => 'required|size:2',
            'certificate_imgs.*' => 'required|mimes:png,jpeg,bmp|max:5120',
        ], $messages)->validate();


		$instructor = Auth::user();

        $cert_imgs = [];
        foreach($request->file("certificate_imgs") as $file) {
        	$cert_imgs[] = Storage::putFile('instructor_files/'.$instructor->id, $file);
        }

        $id_imgs = [];
        foreach($request->file("identification_imgs") as $file) {
        	$id_imgs[] = Storage::putFile('instructor_files/'.$instructor->id, $file);
        }

        
        $instructor->identification_imgs = implode(",", $id_imgs);
        $instructor->professional_cert_imgs = implode(",", $cert_imgs);
        $instructor->phone_number = $request->input("phone_number");
        $instructor->documents_sent_at = date("Y-m-d  H:i:s");
        $instructor->save();


		return redirect()->back();

		
	}



}
