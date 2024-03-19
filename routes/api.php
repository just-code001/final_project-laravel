<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TbluserController;
use App\Http\Controllers\CsrfTokenController;
use App\Http\Controllers\TblclientController;
use App\Http\Controllers\TblvenuesController;
use App\Http\Controllers\StaffAndManagerController;
use App\Http\Controllers\TblvenueBookingController;
use App\Http\Controllers\TblexihibitionController;

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

Route::post('/admin/event/exhibition/add-event',[TblexihibitionController::class,'createExihibition']);
Route::post('/admin/event/exhibition/update-event/{id}',[TblexihibitionController::class,'updateExihibition']);
Route::delete('/admin/event/exhibition/delete-event/{id}',[TblexihibitionController::class,'destroyExihibition']);
Route::get('/admin/event/exhibition/show-event/{id}',[TblexihibitionController::class,'fetchSpecificExihibition']);
Route::get('/admin/event/exhibition/show-events',[TblexihibitionController::class,'fetchAllExihibition']);

Route::get('/admin/event/exhibition/show-art-events',[TblexihibitionController::class,'fetchArtExihibition']);
Route::get('/admin/event/exhibition/show-car-events',[TblexihibitionController::class,'fetchCarExihibition']);