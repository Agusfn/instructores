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


/**
 * Auth
 */
Route::get("login", "User\Auth\LoginController@showLoginForm")->name("user.login");
Route::post("login", "User\Auth\LoginController@login");
Route::post("logout", "User\Auth\LoginController@logout")->name("user.logout");

Route::get("login/{provider}", "User\Auth\SocialLoginController@redirectToSocialLogin")->name("user.login.social");
Route::get("login/{provider}/callback", "User\Auth\SocialLoginController@getSocialCallback");

Route::get("registrarse", "User\Auth\RegisterController@showRegistrationForm")->name("user.register");
Route::post("registrarse", "User\Auth\RegisterController@register");

Route::get("verificar-email/{email}", "User\Auth\VerificationController@verify")->name("user.verify-email");

Route::get("recuperar-cuenta", "User\Auth\ForgotPasswordController@showLinkRequestForm")->name("user.reset-password");
Route::post("recuperar-cuenta", "User\Auth\ForgotPasswordController@sendResetLinkEmail");
Route::get("cambiar-password/{token}", "User\Auth\ResetPasswordController@showResetForm")->name("user.change-password-form");
Route::post("cambiar-password", "User\Auth\ResetPasswordController@reset")->name("user.change-password");






/**
 * User panel
 */

// Account
Route::get("panel/cuenta", "User\AccountDetailsController@index")->name("user.account");
Route::get("panel/cuenta/modificar", "User\AccountDetailsController@showEditAccountForm");
Route::post("panel/cuenta/img_perfil", "User\AccountDetailsController@changeProfilePic");
Route::post("panel/cuenta/modificar", "User\AccountDetailsController@editAccount");

// Reservations
Route::get("panel/reservas", "User\ReservationsController@showList")->name("user.reservations");
Route::get("panel/reservas/{reservation_code}", "User\ReservationsController@details")->name("user.reservation");