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


Route::get("login", "User\Auth\LoginController@showLoginForm")->name("user.login");
Route::post("login", "User\Auth\LoginController@login");
Route::post("logout", "User\Auth\LoginController@logout")->name("user.logout");

Route::get("registro", "User\Auth\RegisterController@showRegistrationForm")->name("user.register");
Route::post("registro", "User\Auth\RegisterController@register");


Route::get("panel/cuenta", "User\AccountDetailsController@index")->name("user.account");
Route::get("panel/reservas", "User\ReservationsController@showList")->name("user.reservations");

