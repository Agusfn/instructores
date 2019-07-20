<?php

/*
|--------------------------------------------------------------------------
| Instructor Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Login
Route::get("instructor/login", "Instructor\Auth\SocialLoginController@showLoginForm")->name("instructor.login");
Route::get("instructor/login/{provider}", "Instructor\Auth\SocialLoginController@redirectToSocialLogin")->name("instructor.login.social");
Route::get("instructor/login/{provider}/callback", "Instructor\Auth\SocialLoginController@getSocialCallback");
Route::post("instructor/logout", "Instructor\Auth\SocialLoginController@logout")->name("instructor.logout");



// Account
Route::get("instructor/panel/cuenta", "Instructor\AccountDetailsController@index")->name("instructor.account");
Route::get("instructor/panel/cuenta/modificar", "Instructor\AccountDetailsController@showEditAccountForm");
Route::post("instructor/panel/cuenta/modificar", "Instructor\AccountDetailsController@editAccount");
Route::post("instructor/panel/cuenta/img_perfil", "Instructor\AccountDetailsController@changeProfilePic");
Route::post("instructor/panel/cuenta/verificar", "Instructor\AccountDetailsController@sendVerifyInfo");



// Service
Route::get("instructor/panel/servicio", "Instructor\ServiceDetailsController@index")->name("instructor.service");

Route::post("instructor/panel/servicio/pausar", "Instructor\ServiceDetailsController@pause");
Route::post("instructor/panel/servicio/activar", "Instructor\ServiceDetailsController@activate");

Route::post("instructor/panel/servicio/subir_imagen", "Instructor\ServiceDetailsController@uploadImage");
Route::post("instructor/panel/servicio/eliminar_imagen", "Instructor\ServiceDetailsController@deleteImage");

Route::post("instructor/panel/servicio/agregar_fechas", "Instructor\ServiceDetailsController@addDateRange");
Route::post("instructor/panel/servicio/eliminar_fechas", "Instructor\ServiceDetailsController@deleteDateRange");

Route::post("instructor/panel/servicio/agregar_horario_bloqueado", "Instructor\ServiceDetailsController@addBlockedTimeblock");
Route::post("instructor/panel/servicio/eliminar_horario_bloqueado", "Instructor\ServiceDetailsController@deleteBlockedTimeblock");

Route::post("instructor/panel/servicio/guardar_cambios", "Instructor\ServiceDetailsController@saveChanges");



// Reservations
Route::get("instructor/panel/reservas", "Instructor\ReservationsController@showList")->name("instructor.reservations");
Route::get("instructor/panel/reservas/{reservation_code}", "Instructor\ReservationsController@details")->name("instructor.reservation");
Route::post("instructor/panel/reservas/{reservation_code}/confirmar", "Instructor\ReservationsController@confirm");
Route::post("instructor/panel/reservas/{reservation_code}/rechazar", "Instructor\ReservationsController@reject");



// Balance
Route::get("instructor/panel/saldo", "Instructor\AccountBalanceController@overview")->name("instructor.balance.overview");

Route::get("instructor/panel/saldo/cta-bancaria", "Instructor\AccountBalanceController@showBankAccountForm")->name("instructor.balance.bank-account");
Route::post("instructor/panel/saldo/cta-bancaria", "Instructor\AccountBalanceController@saveBankAccount");
Route::post("instructor/panel/saldo/actualizar-cta-mp", "Instructor\AccountBalanceController@updateMpAccount");

Route::get("instructor/panel/saldo/retirar", "Instructor\AccountBalanceController@showCollectionForm")->name("instructor.balance.withdraw");
Route::post("instructor/panel/saldo/retirar", "Instructor\AccountBalanceController@createCollectionRequest");
Route::post("instructor/panel/saldo/cancelar-retiro", "Instructor\AccountBalanceController@cancelCollection");

