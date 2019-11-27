<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('jasmin-getsalesorders', 'JasminConnect@allSalesOrders');
Route::get('jasmin-getsalesorders/{serieId}', 'JasminConnect@saleOrderBySerieId');
Route::get('jasmin-getpurchaseorders', 'JasminConnect@allPurchaseOrders');
Route::get('jasmin-getstock', 'JasminConnect@allStock');
