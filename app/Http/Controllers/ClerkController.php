<?php

namespace App\Http\Controllers;

class ClerkController extends Controller
{
    public function showPickingWaves()
    {
        return View('clerk.pickingWaves', ['waves' => WaveController::allWorkerPickingWaves()]);
    }

    public function showPickingRoute($id_wave)
    {
        $zone_list = [
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
        ];

        $last_zone = [
            'zone' => 'G',
            'products' => [
                [
                    'section' => 'G8',
                    'product' => 'P2',
                    'quantity' => '9'
                ]
            ]
        ];

        return View('clerk.pickingRoute', [
            'zones_list' => $zone_list,
            'last_zone' => $last_zone
        ]);
    }

    public function showPackingWaves()
    {
        $waves = [
            [
                'id' => '2',
                'num_orders' => '2',
                'num_products' => '7',
                'date' => '2019-10-24',
            ],
            [
                'id' => '1',
                'num_orders' => '1',
                'num_products' => '9',
                'date' => '2019-10-24',
            ]
        ];

        return View('clerk.packingWaves', ['waves' => $waves]);
    }

    public function showPacking($id_wave)
    {
        $orders = [
            [
                'id' => '4',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
                'items' => [
                    [
                        'id' => '56',
                        'description' => 'AK-47',
                        'zone' => 'D4',
                        'quantity' => '2',
                        'stock' => '9'
                    ]
                ]
            ],
            [
                'id' => '7',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
                'items' => [
                    [
                        'id' => '56',
                        'description' => 'AK-47',
                        'zone' => 'D4',
                        'quantity' => '2',
                        'stock' => '9'
                    ],
                    [
                        'id' => '58',
                        'description' => 'AK-48',
                        'zone' => 'D4',
                        'quantity' => '2',
                        'stock' => '9'
                    ]
                ]
            ]
        ];

        return View('clerk.packing', ['orders' => $orders]);
    }

    public function showDispatchOrders()
    {
        $orders = [
            [
                'id' => '4',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ],
            [
                'id' => '7',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ],
            [
                'id' => '8',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ]
        ];

        return View('clerk.dispatching', ['orders' => $orders]);
    }
}
