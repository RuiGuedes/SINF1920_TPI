<?php

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
    return view('login');
});


Route::view('manager/salesOrders','manager.salesOrders')->name('manager-sales-orders');
Route::view('manager/purchaseOrders','manager.purchaseOrders')->name('manager-purchase-orders');
Route::view('manager/pickingWaves','manager.pickingWaves')->name('manager-picking-waves');
Route::view('manager/replenishment','manager.replenishment')->name('manager-replenishment');
