<?php

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Home pages
Route::get("/", "HomeController@index")->name("home");
Route::get("preguntas-frecuentes", "HomeController@faq")->name("faq");
Route::get("ser-instructor", "HomeController@becomeInstructor")->name("become-instructor");


// Search page
Route::get("buscar", "SearchInstructorsController@search")->name("search");
Route::post("buscar/resultados", "SearchInstructorsController@getResults"); // ajax


// Instructor page
Route::get("instructor/{service_number}", "InstructorServiceController@showDetails")->name("service-page");
Route::post("instructor/{service_number}/calendar", "InstructorServiceController@fetchJsonCalendar"); // ajax


// Reservation process
Route::get("reservar/{service_number}", "ReservationsController@previewReservation");
Route::post("reservar/{service_number}/confirmar", "ReservationsController@reservationForm");
Route::get("reservar/{service_number}/confirmar", "ReservationsController@redirectToService");
Route::post("reservar/{service_number}/procesar", "ReservationsController@processReservation");
Route::get("reservar/resultado/{reservation_code}", "ReservationsController@showResult")->name("reservation.result");
Route::get("reservar/{reservation_code}/reintentar-pago", "ReservationsController@retryPaymentForm")->name("reservation.retry-payment");
Route::post("reservar/{reservation_code}/reintentar-pago", "ReservationsController@retryMpPayment");

























