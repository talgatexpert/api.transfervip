<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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


Route::group(['middleware' => [ 'auth:sanctum'], 'prefix' => 'panel'], function () {
    Route::apiResource('city', \App\Http\Controllers\Api\Admin\CityController::class);
    Route::get('cars', [\App\Http\Controllers\Api\Admin\CarController::class, 'index']);
    Route::post('cars',  [\App\Http\Controllers\Api\Admin\CarController::class,'store']);
    Route::post('cars/{car}',  [\App\Http\Controllers\Api\Admin\CarController::class,'update']);
    Route::delete('cars/{car}',  [\App\Http\Controllers\Api\Admin\CarController::class,'destroy']);
    Route::group(['middleware' => ['sanctum.abilities:settings-can-get,settings-can-edit,settings-can-add']], function (){
        Route::get('settings', [\App\Http\Controllers\Api\Admin\SettingController::class, 'index']);
        Route::post('settings/update/{name}', [\App\Http\Controllers\Api\Admin\SettingController::class, 'update']);
        Route::put('settings/data/{name}', [\App\Http\Controllers\Api\Admin\SettingController::class, 'update']);
        Route::get('settings/{setting}', [\App\Http\Controllers\Api\Admin\SettingController::class, 'show']);
    });
    Route::apiResource('company', \App\Http\Controllers\Api\Admin\CompanyController::class);
    Route::apiResource('bookings', \App\Http\Controllers\Api\Admin\BookingController::class)->except('delete');
    Route::get('/bookings-details/transfers', [\App\Http\Controllers\Api\Admin\BookingController::class, 'transfers']);
    Route::get('/bookings-details/count', [\App\Http\Controllers\Api\Admin\BookingController::class, 'count']);
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UserController::class);
    Route::post('/users/inactive', [\App\Http\Controllers\Api\Admin\UserController::class, 'getNotBindUsers']);
    Route::get('/user', [\App\Http\Controllers\Api\Admin\UserController::class, 'user']);
    Route::put('/user', [\App\Http\Controllers\Api\Admin\UserController::class, 'update_user']);
    Route::get('/user/roles', [\App\Http\Controllers\Api\Admin\UserController::class, 'roles']);
    Route::get('/transfers', [\App\Http\Controllers\Api\Admin\TransferController::class, 'index']);
    Route::get('/transfers/{transfer}', [\App\Http\Controllers\Api\Admin\TransferController::class, 'show']);
    Route::post('/transfers', [\App\Http\Controllers\Api\Admin\TransferController::class, 'store']);
    Route::put('/transfers/{transfer}', [\App\Http\Controllers\Api\Admin\TransferController::class, 'update']);

});
Route::post('contact.blade.php/email', [\App\Http\Controllers\Api\MailController::class, 'sendEmail'] );
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('transfer/cities', [\App\Http\Controllers\Api\CityController::class, 'index']);
Route::get('transfers/{cityFrom}/{cityTo}', [\App\Http\Controllers\Api\TransferController::class, 'index']);
Route::post('bookings', [\App\Http\Controllers\Api\BookingController::class, 'store']);
Route::get('city/{cityName}', [\App\Http\Controllers\Api\CityController::class, 'show']);
Route::post('message/corporate', [\App\Http\Controllers\Api\MailController::class, 'corporate']);
Route::post('message/contact', [\App\Http\Controllers\Api\MailController::class, 'contact']);
Route::post('message/transport', [\App\Http\Controllers\Api\MailController::class, 'transport']);
Route::get('bookings/{booking_token}', [\App\Http\Controllers\Api\BookingController::class, 'show']);
Route::put('bookings/{booking_token}', [\App\Http\Controllers\Api\BookingController::class, 'update']);
Route::put('bookings/{booking_token}/confirm', [\App\Http\Controllers\Api\BookingController::class, 'confirm']);
Route::get('bookings/confirm/{booking_token}/', [\App\Http\Controllers\Api\BookingController::class, 'confirmed']);
Route::put('bookings/{booking_token}/updateCurrency', [\App\Http\Controllers\Api\BookingController::class, 'updateCurrency']);
Route::put('bookings/{booking_token}/updateReturnTrip', [\App\Http\Controllers\Api\BookingController::class, 'updateReturnTrip']);

Route::get('meta', [\App\Http\Controllers\Client\MetaController::class, 'index']);
Route::get('settings/{name}', [\App\Http\Controllers\Client\MetaController::class, 'settings']);



