<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Shipment\CreateShipmentPostController;
use App\Http\Controllers\Shipment\PaginateShipmentsGetController;
use App\Http\Controllers\Shipment\ShowShipmentInfoGetController;
use App\Http\Controllers\Utility\CustomersGetController;
use App\Http\Controllers\Utility\DeliveryZonesGetController;
use App\Http\Controllers\Utility\PaymentMethodsGetController;
use App\Http\Controllers\Utility\VehiclesGetController;
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
        Route::post('/auth/logout', LogoutController::class);


        Route::get('/customers', CustomersGetController::class);
        Route::get('/vehicles', VehiclesGetController::class);
        Route::get('/payment_methods', PaymentMethodsGetController::class);
        Route::get('/delivery_zones', DeliveryZonesGetController::class);
        //Route::get('/utilities/data', UtilityGetController::class);

        Route::post('/shipments', CreateShipmentPostController::class);
        Route::get('/shipments', PaginateShipmentsGetController::class);
        Route::get('/shipments/{shipment:id}', ShowShipmentInfoGetController::class);
    });
});
