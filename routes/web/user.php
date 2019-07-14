<?php

/*
|--------------------------------------------------------------------------
| User Web Routes
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

// Account
Route::get("panel/cuenta", "User\AccountDetailsController@index")->name("user.account");
Route::get("panel/cuenta/modificar", "User\AccountDetailsController@showEditAccountForm");
Route::post("panel/cuenta/img_perfil", "User\AccountDetailsController@changeProfilePic");
Route::post("panel/cuenta/modificar", "User\AccountDetailsController@editAccount");

// Reservations
Route::get("panel/reservas", "User\ReservationsController@showList")->name("user.reservations");
Route::get("panel/reservas/{reservation_code}", "User\ReservationsController@details")->name("user.reservation");
Route::get("panel/reservas/{reservation_code}/crear-reclamo", "User\ReservationsController@showClaimForm")->name("user.reservations.make-claim");
Route::get("panel/reservas/{reservation_code}/reclamo", "User\ReservationsController@showClaimDetails")->name("user.reservations.claim");
Route::post("panel/reservas/{reservation_code}/reclamo", "User\ReservationsController@submitClaim");
Route::post("panel/reservas/{reservation_code}/reclamo/mensaje", "User\ReservationsController@submitClaimMessage");