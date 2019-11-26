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
    return view('login', ['route' => 'picking-waves']);
});

Route::view('/','login', ['route' => 'picking-waves'])->name('login');
Route::view('layout','layout')->name('layout');

Route::view('manager/salesOrders','manager.salesOrders')->name('manager-sales-orders');
Route::view('manager/purchaseOrders','manager.purchaseOrders')->name('manager-purchase-orders');
Route::view('manager/pickingWaves','manager.pickingWaves')->name('manager-picking-waves');
Route::view('manager/replenishment','manager.replenishment', 
    [
        'products' => [
            [
                'id'=>'56',
                'description'=>'AK-47',
                'zone'=>'D4',
                'stock' => '9',
                'status' => 'Last Units'
            ],
            [
                'id'=>'56',
                'description'=>'AK-47',
                'zone'=>'D4',
                'stock' => '9'
            ],
            [
                'id'=>'58',
                'description'=>'AK-48',
                'zone'=>'D4',
                'stock' => '0',
                'status' => 'Out of Stock'
            ],
            [
                'id'=>'58',
                'description'=>'Desert Eagle',
                'zone'=>'B3',
                'stock' => '300'
            ]
        ]
    ])->name('manager-replenishment');

Route::view('clerk/packingWaves','clerk.packingWaves')->name('packing-waves');
Route::view('clerk/packing','clerk.packing')->name('packing');
Route::view('clerk/pickingWaves','clerk.pickingWaves')->name('picking-waves');
Route::view('clerk/dispatching','clerk.dispatching')->name('dispatching');
Route::view('clerk/pickingRoute','clerk.pickingRoute',[
    'zones_list' => [
        [
            'zone' => 'A',
            'products' => [
                [
                    'section' => 'A3',
                    'product' => 'Desert Eagle',
                    'quantity' => '4'
                ],
                [
                    'section' => 'A4',
                    'product' => 'M1911',
                    'quantity' => '2'
                ]
            ]
        ],
        [
            'zone' => 'B',
            'products' => [
                [
                    'section' => 'B1',
                    'product' => 'Desert Eagle',
                    'quantity' => '4'
                ],
                [
                    'section' => 'B6',
                    'product' => 'M1911',
                    'quantity' => '2'
                ]
            ]
        ],
        [
            'zone' => 'C',
            'products' => [
                [
                    'section' => 'C3',
                    'product' => 'C4',
                    'quantity' => '4'
                ],
                [
                    'section' => 'C8',
                    'product' => 'M1911',
                    'quantity' => '2'
                ]
            ]
        ],
        [
            'zone' => 'D',
            'products' => [
                [
                    'section' => 'D2',
                    'product' => 'MP7',
                    'quantity' => '4'
                ]
            ]
        ],
        [
            'zone' => 'E',
            'products' => [
                [
                    'section' => 'E2',
                    'product' => 'MP7',
                    'quantity' => '4'
                ]
            ]
        ],
        [
            'zone' => 'F',
            'products' => [
                [
                    'section' => 'F8',
                    'product' => 'MP7',
                    'quantity' => '4'
                ]
            ]
        ]
    ],
    'last_zone' => [
        'zone' => 'G',
        'products' => [
            [
                'section' => 'G8',
                'product' => 'P2',
                'quantity' => '9'
            ]
        ]
    ]
])->name('picking-route');