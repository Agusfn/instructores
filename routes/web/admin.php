<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Auth
Route::get("admin/login", "Admin\Auth\LoginController@showLoginForm")->name("admin.login");
Route::post("admin/login", "Admin\Auth\LoginController@login");
Route::post("admin/logout", "Admin\Auth\LoginController@logout")->name("admin.logout");


// Home
Route::get("admin", "Admin\HomeController@index")->name("admin.home");


// Instructors
Route::get("admin/instructores", "Admin\InstructorsController@list")->name("admin.instructors.list");
Route::get("admin/instructores/{id}", "Admin\InstructorsController@details")->name("admin.instructors.details");
Route::post("admin/instructores/{id}/aprobar", "Admin\InstructorsController@approve");
Route::post("admin/instructores/{id}/rechazar_doc", "Admin\InstructorsController@rejectDocs");
Route::get("admin/instructores/{id}/documentos/{filename}", "Admin\InstructorsController@displayDocumentImg")->name("admin.instructors.documents");
Route::post("admin/instructores/{id}/suspender", "Admin\InstructorsController@toggleSuspend");
Route::post("admin/instructores/{id}/eliminar", "Admin\InstructorsController@delete");
Route::post("admin/instructores/{id}/pausa", "Admin\InstructorsController@toggleAdminPause");
Route::post("admin/instructores/{id}/resend-verification-email", "Admin\InstructorsController@resendVerifEmail");

// Users
Route::get("admin/usuarios", "Admin\UsersController@list")->name("admin.users.list");
Route::get("admin/usuarios/{id}", "Admin\UsersController@details")->name("admin.users.details");
Route::post("admin/usuarios/{id}/suspender", "Admin\UsersController@toggleSuspend");
Route::post("admin/usuarios/{id}/eliminar", "Admin\UsersController@delete");
Route::post("admin/usuarios/{id}/resend-verification-email", "Admin\UsersController@resendVerifEmail");


// Reservations
Route::get("admin/reservas", "Admin\ReservationsController@list")->name("admin.reservations.list");
Route::get("admin/reservas/{id}", "Admin\ReservationsController@details")->name("admin.reservations.details");
Route::post("admin/reservas/{id}/cancelar", "Admin\ReservationsController@cancel");


// Instructor collections
Route::get("admin/pagos-instructores", "Admin\InstructorCollectionsController@list")->name("admin.instructor-collections.list");
Route::post("admin/pagos-instructores/confirmar", "Admin\InstructorCollectionsController@confirmCollection");
Route::post("admin/pagos-instructores/rechazar", "Admin\InstructorCollectionsController@rejectCollection");


// Settings
Route::get("admin/opciones", "Admin\SettingsController@index")->name("admin.settings");
Route::post("admin/opciones", "Admin\SettingsController@save");


// Account
Route::get("admin/cuenta", "Admin\AccountSettingsController@index")->name("admin.account");
Route::post("admin/cuenta/cambiar_password", "Admin\AccountSettingsController@changePassword");