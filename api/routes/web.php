<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return "Welcome ";
});

Route::post('deploy', [\App\Http\Controllers\Deployment\DeployController::class, 'deploy']);
Route::post('login', [AuthController::class, 'login']);
Route::get('login', function () {
    return view('welcome');
})->name('login');

