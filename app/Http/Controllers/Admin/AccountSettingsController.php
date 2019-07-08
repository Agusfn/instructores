<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AccountSettingsController extends Controller
{
    	

    public function __construct()
    {
    	$this->middleware('auth:admin');
    }


    /**
     * Show account settings form.
     * @return [type] [description]
     */
    public function index()
    {
    	return view("admin.account");
    }


    /**
     * Change admin password.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "current_password" => "required",
            "new_password" => "required|min:6|max:100",
            "new_password_repeat" => "required|same:new_password"
        ]);

        if($validator->fails())
            return redirect()->back()->withErrors($validator, "change_password");


        $admin = Auth::user();

        if(!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(["current_password" => "La contraseña actual ingresada es inválida."], "change_password");
        }

        if(Hash::check($request->new_password, $admin->password)) {
            return redirect()->back()->withErrors(["new_password" => "La nueva contraseña no puede ser igual a la actual."], "change_password");
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();


        request()->session()->flash('pwd-change-success');
        return redirect()->back();
    }


}
