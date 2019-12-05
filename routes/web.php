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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login', ['route' => 'picking-waves']);
});

Route::post('login', 'Auth\LoginController@login')->name('login-action');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::view('/','login', ['route' => 'picking-waves'])->name('login');
Route::view('layout','layout')->name('layout');

Route::get('manager/salesOrders','ManagerController@showSalesOrders')->name('manager-sales-orders');
Route::get('manager/purchaseOrders','ManagerController@showPurchaseOrders')->name('manager-purchase-orders');
Route::get('manager/pickingWaves','ManagerController@showPickingWaves')->name('manager-picking-waves');
Route::get('manager/replenishment','ManagerController@showReplenishment')->name('manager-replenishment');

Route::post('/manager/replenishment/create-purchase-order','PurchaseOrdersController@createPurchaseOrder');
Route::post('/manager/replenishment/allocate-purchase-order','PurchaseOrdersController@allocatePurchaseOrder');
Route::post('/manager/createPickingWave','WaveController@createPickingWave');

Route::get('clerk/packingWaves','ClerkController@showPackingWaves')->name('packing-waves');
Route::get('clerk/packing/{id_wave}','ClerkController@showPacking')->name('packing');
Route::get('clerk/pickingWaves','ClerkController@showPickingWaves')->name('picking-waves');
Route::get('clerk/dispatching','ClerkController@showDispatchOrders')->name('dispatching');
Route::get('clerk/pickingRoute/{id_wave}','ClerkController@showPickingRoute')->name('picking-route');