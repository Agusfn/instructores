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

//Auth::routes();



Route::get("/", "HomeController@index")->name("home");
Route::get("preguntas-frecuentes", "HomeController@faq")->name("faq");
Route::get("ser-instructor", "HomeController@becomeInstructor")->name("become-instructor");


Route::get("instructor/{service_number}", "InstructorServiceController@showDetails")->name("service-page");



Route::get('email/verificar', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verificar/{email}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/reenviar', 'Auth\VerificationController@resend')->name('verification.resend');


























