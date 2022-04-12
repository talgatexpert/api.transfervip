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


Route::group(['middleware' => [ 'auth:sanctum']], function () {
    Route::apiResource('city', \App\Http\Controllers\Api\Admin\CityController::class);
    Route::get('cars', [\App\Http\Controllers\Api\Admin\CarController::class, 'index']);
    Route::post('cars',  [\App\Http\Controllers\Api\Admin\CarController::class,'store']);
    Route::post('cars/{car}',  [\App\Http\Controllers\Api\Admin\CarController::class,'update']);
    Route::delete('cars/{car}',  [\App\Http\Controllers\Api\Admin\CarController::class,'destroy']);
    Route::apiResource('settings', \App\Http\Controllers\Api\Admin\SettingController::class)
        ->middleware('sanctum.abilities:settings-can-get,settings-can-edit,settings-can-add');
    Route::apiResource('company', \App\Http\Controllers\Api\Admin\CompanyController::class);
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UserController::class);
    Route::post('/users/inactive', [\App\Http\Controllers\Api\Admin\UserController::class, 'getNotBindUsers']);
    Route::get('/user', [\App\Http\Controllers\Api\Admin\UserController::class, 'user']);
    Route::get('/user/roles', [\App\Http\Controllers\Api\Admin\UserController::class, 'roles']);
    Route::get('/transfers', [\App\Http\Controllers\Api\Admin\TransferController::class, 'index']);
    Route::get('/transfers/{transfer}', [\App\Http\Controllers\Api\Admin\TransferController::class, 'show']);
    Route::post('/transfers', [\App\Http\Controllers\Api\Admin\TransferController::class, 'store']);
    Route::put('/transfers/{transfer}', [\App\Http\Controllers\Api\Admin\TransferController::class, 'update']);

});

Route::post('contact/email', [\App\Http\Controllers\Api\MailController::class, 'sendEmail'] );
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('transfer/cities', [\App\Http\Controllers\Api\CityController::class, 'index']);


