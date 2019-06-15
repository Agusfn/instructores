<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Login
Route::get("login", "User\Auth\SocialLoginController@showLoginForm")->name("user.login");
Route::get("login/{provider}", "User\Auth\SocialLoginController@redirectToSocialLogin")->name("user.login.social");
Route::get("login/{provider}/callback", "User\Auth\SocialLoginController@getSocialCallback");
Route::post("logout", "User\Auth\SocialLoginController@logout")->name("user.logout");

//Route::post("login", "User\Auth\LoginController@login");
//Route::post("logout", "User\Auth\LoginController@logout")->name("user.logout");
//Route::get("registro", "User\Auth\RegisterController@showRegistrationForm")->name("user.register");
//Route::post("registro", "User\Auth\RegisterController@register");


// Account
Route::get("panel/cuenta", "User\AccountDetailsController@index")->name("user.account");
Route::get("panel/cuenta/modificar", "User\AccountDetailsController@showEditAccountForm");
Route::post("panel/cuenta/img_perfil", "User\AccountDetailsController@changeProfilePic");
Route::post("panel/cuenta/modificar", "User\AccountDetailsController@editAccount");



Route::get("panel/reservas", "User\ReservationsController@showList")->name("user.reservations");
Route::get("panel/reservas/{reservation_code}", "User\ReservationsController@details")->name("user.reservation");