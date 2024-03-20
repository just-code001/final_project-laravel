<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TbluserController;
use App\Http\Controllers\TblthemeController;
use App\Http\Controllers\CsrfTokenController;
use App\Http\Controllers\TblclientController;
use App\Http\Controllers\TblvenuesController;
use App\Http\Controllers\TblconcertController;
use App\Http\Controllers\TblPackageController;
use App\Http\Controllers\TblexihibitionController;
use App\Http\Controllers\StaffAndManagerController;
use App\Http\Controllers\TblvenueBookingController;
use App\Http\Controllers\TblconcertbookingController;
use App\Http\Controllers\TblweddingbookingController;
use App\Http\Controllers\TblbirthdaybookingController;
use App\Http\Controllers\TblexhibitionbookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// csrf token generate route ==================
// Route::get('/csrf-token',[CsrfTokenController::class,'getCsrfToken'] );

Route::post('/client/register', [TblclientController::class, 'register']);
Route::post('/client/verify-otp', [TblclientController::class, 'verifyOtp']);
Route::post('/client/login', [TblclientController::class, 'login']);
Route::put('/client/profile/update-profile/{client_id}', [TblclientController::class, 'updateProfile']);
Route::get('/admin/show-clients',[TblclientController::class,'fetchClients']);
Route::get('/client/show-single-client/{client_id}',[TblclientController::class,'ftechSingleClient']);

// admin routes =======
Route::post('/admin/login',[TbluserController::class,"adminLogin"]);
Route::post('/staff-manager/login',[StaffAndManagerController::class,'loginStaffOrManager']);

// staff and manager routes ==========
Route::post('/admin/add-user',[StaffAndManagerController::class,'create']);
Route::get('/admin/show-user/{user_id}',[StaffAndManagerController::class,'show']);
Route::put('/admin/update-user/{user_id}',[StaffAndManagerController::class,'update']);
Route::delete('/admin/delete-user/{user_id}',[StaffAndManagerController::class,'destroy']);
Route::get('/admin/show-users',[StaffAndManagerController::class,'fetchUsers']);

Route::get('/manager/show-staff-users',[StaffAndManagerController::class,'fetchStaffUsers']);
Route::get('/admin/show-manager-users',[StaffAndManagerController::class,'fetchManagerUsers']);

// venue ============================
Route::post('/admin/venue/add-venue',[TblvenuesController::class,'createVenue']);
Route::post('/admin/venue/update-venue/{id}',[TblvenuesController::class,'updateVenue']);
Route::get('/admin/venue/show-venues',[TblvenuesController::class,'fetchAllVenuesAndDetails']);
Route::delete('/admin/venue/delete-venue/{id}',[TblvenuesController::class,'destroyVenueAndDetail']);
Route::get('/admin/venue/show-venue/{id}',[TblvenuesController::class,'fetchSepcificVenueAndDetail']);
Route::get('/client/show-venue/{city}',[TblvenuesController::class,'getVenuesByCity']);

// venue booking==========================
Route::post('/client/venue/book-venue',[TblvenueBookingController::class,'createBooking']);
Route::get('/admin/venue/show-venuebooking',[TblvenueBookingController::class,'fetchBookingsForAdmin']);
Route::get('/admin/venue/show-single-venuebooking/{id}',[TblvenueBookingController::class,'fetchSepcificVenueBookingDetail']);
Route::put('/admin/venue/update-payment-status/{id}',[TblvenueBookingController::class,'updatePaymentStatus']);

Route::post('/client/wedding/wedding-booking',[TblweddingbookingController::class,'createWeddingBooking']);
Route::get('/admin/wedding/show-wedding-bookings',[TblweddingbookingController::class,'fetchBookingsForAdmin']);

Route::post('/client/birthday/birthday-booking',[TblbirthdaybookingController::class,'createBirthdayBooking']);
Route::get('/admin/birthday/show-birthday-bookings',[TblbirthdaybookingController::class,'fetchBirthdayBookingForAdmin']);

// concert ================================
Route::post('/admin/event/concert/add-concert',[TblconcertController::class,'createConcert']);
Route::post('/admin/event/concert/update-concert/{id}',[TblconcertController::class,'updateConcert']);
Route::get('/admin/event/concert/show-concerts',[TblconcertController::class,'fetchAllConcerts']);
Route::delete('/admin/event/concert/delete-concert/{id}',[TblconcertController::class,'destroyConcert']);
Route::get('/admin/event/concert/show-concert/{id}',[TblconcertController::class,'fetchSepcificConcert']);
Route::post('/client/event/concert/ticket/book-ticket',[TblconcertbookingController::class,'createBooking']);

Route::post('/admin/event/exhibition/add-event',[TblexihibitionController::class,'createExihibition']);
Route::post('/admin/event/exhibition/update-event/{id}',[TblexihibitionController::class,'updateExihibition']);
Route::delete('/admin/event/exhibition/delete-event/{id}',[TblexihibitionController::class,'destroyExihibition']);
Route::get('/admin/event/exhibition/show-event/{id}',[TblexihibitionController::class,'fetchSpecificExihibition']);
Route::get('/admin/event/exhibition/show-events',[TblexihibitionController::class,'fetchAllExihibition']);
Route::post('/client/event/exhibition/ticket/book-ticket',[TblexhibitionbookingController::class,'createExhibitionBooking']);

Route::get('/admin/event/exhibition/show-art-events',[TblexihibitionController::class,'fetchArtExihibition']);
Route::get('/admin/event/exhibition/show-car-events',[TblexihibitionController::class,'fetchCarExihibition']);

//package====================

Route::post('/admin/event/birthday/add-package',[TblPackageController::class,'createPackage']);
Route::post('/admin/event/birthday/update-package/{id}',[TblPackageController::class,'updatePackage']);
Route::delete('/admin/event/birthday/delete-package/{id}',[TblPackageController::class,'destroyPackage']);
Route::get('/admin/event/birthday/show-package/{id}',[TblPackageController::class,'fetchSpecificPackage']);
Route::get('/admin/event/birthday/show-packages',[TblPackageController::class,'fetchAllPackage']);

//themes=========================

Route::post('/admin/event/birthday/add-theme',[TblthemeController::class,'createTheme']);
Route::post('/admin/event/birthday/update-theme/{id}',[TblthemeController::class,'updateTheme']);
Route::delete('/admin/event/birthday/delete-theme/{id}',[TblthemeController::class,'destroyTheme']);
Route::get('/admin/event/birthday/show-theme/{id}',[TblthemeController::class,'fetchSpecificTheme']);
Route::get('/admin/event/birthday/show-themes',[TblthemeController::class,'fetchAllTheme']);
