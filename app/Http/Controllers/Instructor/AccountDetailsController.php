<?php

namespace App\Http\Controllers\Instructor;

use \Hash;
use Validator;
use App\Lib\Helpers\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AccountDetailsController extends Controller
{


	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		$this->middleware("auth:instructor");
	}
    


	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function index()
	{
		return view("instructor.account.details")->with("instructor", Auth::user());
	}



	/**
	 * Show account details modification form.
	 * @return [type] [description]
	 */
	public function showEditAccountForm()
	{
		$instructor = Auth::user();
		return view("instructor.account.edit")->with("instructor", $instructor);
	}


	/**
	 * Update instructor account changes.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function editAccount(Request $request)
	{
		$request->validate([
			"name" => "required|string|between:3,50",
			"surname" => "required|string|between:3,50",
			"phone_number" => "nullable|string|between:5,30|regex:/^[0-9+ -]*$/",
			"instagram_username" => "nullable|string|max:30|regex:/^[\w\d._]{1,30}$/",
		]);

		$instructor = Auth::user();

		$instructor->fill([
			"name" => $request->name,
			"surname" => $request->surname,
			"instagram_username" => $request->instagram_username
		]);

		if($request->filled("phone_number"))
			$instructor->phone_number = $request->phone_number;


		$instructor->save();

		return redirect()->route("instructor.account");
	}



	/**
	 * Change instructor profile picture through ajax POST request with file.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function changeProfilePic(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"profile_pic" => "required|file|mimes:jpeg|max:5120"
		]);

		if($validator->fails()) {
			return response($validator->messages()->first(), 422);
		}

		$instructor = Auth::user();

		$image = Image::make($request->file("profile_pic"));
		$instructor->setProfilePic($image);

		return response("OK", 200);
	}




	/**
	 * [sendVerifyInfo description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function sendVerifyInfo(Request $request)
	{

		$messages = [
			"identification_imgs.max" => "No se pueden subir más de :max imágenes.",
			"certificate_imgs.size" => "Debés subir :size imágenes, una de cada cara del certificado."
		];

		$validator = Validator::make($request->all(), [
            'identification_imgs' => 'required|max:2',
            'identification_imgs.*' => 'required|mimes:png,jpeg|max:5120',
            'certificate_imgs' => 'required|size:2',
            'certificate_imgs.*' => 'required|mimes:png,jpeg|max:5120',
        ], $messages);

		if($validator->fails()) {
			return redirect()->to(url()->previous()."#verificar-cuenta")->withErrors($validator);
		}

		$instructor = Auth::user();

		if(!$instructor->phone_number || !$instructor->profile_picture)
			return redirect()->back()->withErrors("Instructor does not have number and profile picture.");

		if($instructor->isApproved() || $instructor->approvalDocsSent())
			return redirect()->back()->withErrors("El instructor ya está aprobado o ya envió la documentación.");

		
        $certImgNames = [];
        foreach($request->file("certificate_imgs") as $file)  {
        	$image = Images::toJpgAndResize(Image::make($file), 1920, 1080);
        	$certImgNames[] = basename(Images::saveImgWithRandName("local", "instructor_documents/".$instructor->id, $image));
        }

        $idImgNames = [];
        foreach($request->file("identification_imgs") as $file) {
        	$image = Images::toJpgAndResize(Image::make($file), 1920, 1080);
        	$idImgNames[] = basename(Images::saveImgWithRandName("local", "instructor_documents/".$instructor->id, $image));
        }
        
        $instructor->identification_imgs = implode(",", $idImgNames);
        $instructor->professional_cert_imgs = implode(",", $certImgNames);
        $instructor->documents_sent_at = date("Y-m-d  H:i:s");
        $instructor->save();


		return redirect()->back();

		
	}



}
