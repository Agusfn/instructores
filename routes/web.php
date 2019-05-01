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




Route::get('email/verificar', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verificar/{email}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/reenviar', 'Auth\VerificationController@resend')->name('verification.resend');





Route::get("login", "User\Auth\LoginController@showLoginForm")->name("user.login");
Route::post("login", "User\Auth\LoginController@login");
Route::post("logout", "User\Auth\LoginController@logout")->name("user.logout");

Route::get("registro", "User\Auth\RegisterController@showRegistrationForm")->name("user.register");
Route::post("registro", "User\Auth\RegisterController@register");


Route::get("panel/cuenta", "User\AccountDetailsController@index")->name("user.account");
Route::get("panel/reservas", "User\ReservationsController@showList")->name("user.reservations");









Route::get("instructor/login", "Instructor\Auth\LoginController@showLoginForm")->name("instructor.login");
Route::post("instructor/login", "Instructor\Auth\LoginController@login");
Route::post("instructor/logout", "Instructor\Auth\LoginController@logout")->name("instructor.logout");

Route::get("instructor/registro", "Instructor\Auth\RegisterController@showRegistrationForm")->name("instructor.register");
Route::post("instructor/registro", "Instructor\Auth\RegisterController@register");

Route::get("instructor/panel/cuenta", "Instructor\AccountDetailsController@index")->name("instructor.account");
Route::get("instructor/panel/cuenta/password", "Instructor\AccountDetailsController@showChangePasswordForm");
Route::post("instructor/panel/cuenta/password", "Instructor\AccountDetailsController@changePassword");

Route::post("instructor/panel/cuenta/verificar", "Instructor\AccountDetailsController@sendVerifyInfo");
Route::get("instructor/panel/servicio", "Instructor\ServiceDetailsController@index")->name("instructor.service");
Route::get("instructor/panel/reservas", "Instructor\ReservationsController@showList")->name("instructor.reservations");
Route::get("instructor/panel/saldo", "Instructor\AccountBalanceController@index")->name("instructor.balance");












Route::get("admin/login", "Admin\Auth\LoginController@showLoginForm")->name("admin.login");
Route::post("admin/login", "Admin\Auth\LoginController@login");
Route::post("admin/logout", "Admin\Auth\LoginController@logout")->name("admin.logout");


Route::get("admin", "Admin\HomeController@index")->name("admin.home");

Route::get("admin/instructores", "Admin\InstructorsController@list")->name("admin.instructors.list");
Route::get("admin/instructores/{id}", "Admin\InstructorsController@details")->name("admin.instructors.details");
Route::post("admin/instructores/{id}/aprobar", "Admin\InstructorsController@approve");
Route::post("admin/instructores/{id}/rechazar_doc", "Admin\InstructorsController@rejectDocs");

Route::get("admin/usuarios", "Admin\UsersController@list")->name("admin.users.list");
Route::get("admin/usuarios/{id}", "Admin\UsersController@details")->name("admin.users.details");

Route::get("admin/reservas", "Admin\ReservationsController@list")->name("admin.reservations.list");
Route::get("admin/opciones", "Admin\SettingsController@index")->name("admin.settings");
