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

Route::get('jasmin-getsalesorders', 'SalesOrdersController@allSalesOrders');
Route::get('jasmin-getsalesorders/{serieId}', 'SalesOrdersController@saleOrderBySerieId');
Route::get('jasmin-getpurchaseorders', 'PurchaseOrdersController@allPurchaseOrders');
Route::get('jasmin-getstock', 'InventoryController@allStock');
