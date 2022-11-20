<?php

use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Customer\OrderController;
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

Route::controller(AuthController::class)->group(function (){
    Route::post('login', 'login');
    Route::post('register', 'register');

    Route::get('products', [\App\Http\Controllers\Api\Public\ProductController::class, 'index']);
    Route::get('products/{slug}', [\App\Http\Controllers\Api\Public\ProductController::class, 'show']);
});

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('logout', [AuthController::class, 'logout']);

    #Customer
    Route::group(['middleware' => ['auth:sanctum', 'customer']], function(){
        Route::resource('orders', OrderController::class)->except('create', 'edit');
    });

    #Admin Route
    Route::group(['middleware' => ['auth:sanctum', 'admin'], 'prefix' => 'admin'], function(){
        Route::resource('products', ProductController::class)->except('create', 'edit', 'index');
    });
});
