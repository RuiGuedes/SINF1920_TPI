<?php

namespace App\Http\Controllers;

use App\PickingWaves;
use App\PickingWavesState;
use App\Products;
use Illuminate\Support\Facades\Auth;

class ClerkController extends Controller
{
    public function showPickingWaves()
    {
        return View('clerk.pickingWaves', ['waves' => WaveController::allWorkerPickingWaves()]);
    }

    public function showPickingRoute($id_wave)
    {
        PickingWaves::assignToUser($id_wave, Auth::user()->getAuthIdentifier());

        $states = PickingWavesState::getPickingWaveStatesByWaveId($id_wave);
        $zone_list = [];

        foreach ($states as $state) {
            $item = Products::getProductByID($state->product_id);
            $product = [
                'section' => $item->warehouse_section,
                'product' => $item->description,
                'quantity' => $state->desired_qnt
            ];

            $zone = $item->warehouse_section[0];
            $section = $item->warehouse_section;

            if (array_key_exists($zone, $zone_list)){
                $zone_list[$zone]['products'][$section] = $product;
            } else {
                $zone_list[$zone] = [
                    'zone' => $zone,
                    'products' => [$section => $product]
                ];
            }
        }

        ksort($zone_list);

        foreach ($zone_list as &$zone)
            ksort($zone['products']);

        return View('clerk.pickingRoute', [ 'zones_list' => array_values($zone_list) ]);
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
