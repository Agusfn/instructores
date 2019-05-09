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



Route::get("instructor/login", "Instructor\Auth\LoginController@showLoginForm")->name("instructor.login");
Route::post("instructor/login", "Instructor\Auth\LoginController@login");
Route::post("instructor/logout", "Instructor\Auth\LoginController@logout")->name("instructor.logout");

Route::get("instructor/registro", "Instructor\Auth\RegisterController@showRegistrationForm")->name("instructor.register");
Route::post("instructor/registro", "Instructor\Auth\RegisterController@register");

// Account
Route::get("instructor/panel/cuenta", "Instructor\AccountDetailsController@index")->name("instructor.account");
Route::get("instructor/panel/cuenta/password", "Instructor\AccountDetailsController@showChangePasswordForm");
Route::post("instructor/panel/cuenta/password", "Instructor\AccountDetailsController@changePassword");
Route::post("instructor/panel/cuenta/cambiar_tel", "Instructor\AccountDetailsController@changePhone");
Route::post("instructor/panel/cuenta/img_perfil", "Instructor\AccountDetailsController@changeProfilePic");
Route::post("instructor/panel/cuenta/cambiar_instagram", "Instructor\AccountDetailsController@changeInstagram");
Route::post("instructor/panel/cuenta/verificar", "Instructor\AccountDetailsController@sendVerifyInfo");

// Service
Route::get("instructor/panel/servicio", "Instructor\ServiceDetailsController@index")->name("instructor.service");
Route::post("instructor/panel/servicio/agregar_fechas", "Instructor\ServiceDetailsController@addDateRange");
Route::post("instructor/panel/servicio/eliminar_fechas", "Instructor\ServiceDetailsController@removeDateRange");

Route::post("instructor/panel/servicio/subir_imagen", "Instructor\ServiceDetailsController@uploadImage");
Route::post("instructor/panel/servicio/eliminar_imagen", "Instructor\ServiceDetailsController@deleteImage");
Route::post("instructor/panel/servicio/guardar_cambios", "Instructor\ServiceDetailsController@saveChanges");


// Reservations
Route::get("instructor/panel/reservas", "Instructor\ReservationsController@showList")->name("instructor.reservations");



// Balance
Route::get("instructor/panel/saldo", "Instructor\AccountBalanceController@index")->name("instructor.balance");