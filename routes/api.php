<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::middleware('admin')->group(function () {
    // Route::post('/createUser', [AuthController::class, 'createUser']);
    Route::apiResource('user', UserController::class);
});
Route::middleware('driver')->group(function () {
});
Route::middleware('employee')->group(function () {
    Route::post('/createCoupon', [CouponsController::class, 'createCoupon']);
    Route::post('/exportPDF', [CouponsController::class, 'exportPDF']);
    Route::apiResource('coupon', CouponsController::class)->except(['store', 'index']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => '',
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
