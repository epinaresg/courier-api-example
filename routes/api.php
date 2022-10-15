<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Customer\CustomerGetController;
use App\Http\Controllers\Shipment\CreateShipmentPostController;
use App\Http\Controllers\Utility\UtilityGetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => '/v1'], function () {
    Route::post('/auth/login', LoginController::class);
    Route::post('/auth/refresh', RefreshTokenController::class);
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::get('/utilities/data', UtilityGetController::class);

        Route::post('/shipments', CreateShipmentPostController::class);
        ///Route::get('/shipment', CustomerGetController::class);
    });
});
