<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightsController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrdersController;
use PHPUnit\TextUI\XmlConfiguration\Group;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


 
Route::post('/auth/regester', [AuthController::class, 'regester']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/deleteaccount', [AuthController::class, 'destroyAccount']);
    Route::post('/auth/update', [AuthController::class, 'update']);
    Route::get('/media/user/avatarimage',[MediaController::class, 'user_avatarimage']);
    Route::post('/flights/create', [FlightsController::class, 'create']);
    Route::post('/flights/update/{id}', [FlightsController::class, 'update']);
    Route::get('/flights/delete/{id}',  [FlightsController::class, 'delete']);
    Route::get('/flights/postedflights', [FlightsController::class, 'postedFilghts']); 
    Route::get('/orders/setorder',[OrdersController::class, 'setOrder']);
    Route::get('/orders/getorders',[OrdersController::class, 'getOrders']);
    Route::post('/orders/setacceptstatue/{order_id}',[OrdersController::class, 'setAcceptStatue']); 
    Route::get('/flights', [FlightsController::class, 'read']);
    Route::get('/flights/{id}', [FlightsController::class, 'readItem']);
    Route::get('/media/flights/cartsimages/{image}',[MediaController::class, 'flight_cartimage']);
    // Route::get('/analytics/getdata', [AnalyticsController::class, 'getData']); 
}); 