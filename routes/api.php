<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TbluserController;
use App\Http\Controllers\CsrfTokenController;
use App\Http\Controllers\TblclientController;

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

// admin routes =======
Route::post('/admin/login',[TbluserController::class,"adminLogin"]);
