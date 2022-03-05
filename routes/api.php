<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\Users\UserController;
use \App\Http\Controllers\Stocks\StockController;
use \App\Http\Controllers\Histories\HistoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'authenticate'])->name('api.login');
Route::post('/users/store', [UserController::class, 'store'])->name('api.users.store');

Route::group(['middleware' => 'jwt.auth'], function (){
    Route::get('/stock', [StockController::class, 'show']);
    Route::get('/history', [HistoryController::class, 'index']);
});
