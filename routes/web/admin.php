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


// Users
Route::get("admin/usuarios", "Admin\UsersController@list")->name("admin.users.list");
Route::get("admin/usuarios/{id}", "Admin\UsersController@details")->name("admin.users.details");


// Reservations
Route::get("admin/reservas", "Admin\ReservationsController@list")->name("admin.reservations.list");
Route::get("admin/reservas/{id}", "Admin\ReservationsController@details")->name("admin.reservations.details");


// Settings
Route::get("admin/opciones", "Admin\SettingsController@index")->name("admin.settings");
Route::post("admin/opciones", "Admin\SettingsController@save");