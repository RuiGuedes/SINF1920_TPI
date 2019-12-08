<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Data\DataWave;
use Illuminate\View\View;

class ClerkController extends Controller
{
    /**
     * Retrieves picking waves view
     *
     * @return View
     */
    public function showPickingWaves()
    {
        return View('clerk.pickingWaves', ['waves' => DataWave::allWorkerPickingWaves()]);
    }

    /**
     * Retrieves picking wave route view
     *
     * @param $id_wave
     * @return View
     */
    public function showPickingRoute($id_wave)
    {
        return View('clerk.pickingRoute', [ 'zones_list' => DataWave::pickingRoute($id_wave)]);
    }

    /**
     * Retrieves packing waves view
     *
     * @return View
     */
    public function showPackingWaves()
    {
        return View('clerk.packingWaves', ['waves' => DataWave::allWorkerPackingWaves()]);
    }

    /**
     * Retrieves packing wave information view
     *
     * @param $id_wave
     * @return View
     */
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
