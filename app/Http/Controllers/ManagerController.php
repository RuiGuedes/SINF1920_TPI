<?php

namespace App\Http\Controllers;

use App\PickingWaves;
use App\PickingWavesState;
use App\Products;
use App\SalesOrders;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ManagerController extends Controller
{
    /**
     * Display the sales orders page with all the sales orders to treat
     */
    public function showSalesOrders()
    {
        $orders = SalesOrdersController::allSalesOrders();

        // Remove sales orders already in existing picking waves
        for ($i = 0; $i < count($orders); $i++) {
            if (SalesOrders::getExists($orders[$i]['id'])) {
                array_splice($orders, $i, 1);
                $i--;
            }
        }

        return View('manager.salesOrders', ['sales' => $orders]);
    }

    /**
     * Display the purchase orders page with all the purchase orders to allocate in the warehouse
     */
    public function showPurchaseOrders()
    {
        $orders = [
            [
                'id' => '4',
                'owner' => 'C0078',
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
            ],
            [
                'id' => '8',
                'owner' => 'C0054',
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
                        'stock' => '90'
                    ],
                    [
                        'id' => '98',
                        'description' => 'Desert Eagle',
                        'zone' => 'B3',
                        'quantity' => '40',
                        'stock' => '300'
                    ]
                ]
            ]
        ];

        return View('manager.purchaseOrders', ['purchases' => $orders]);
    }

    /**
     * Display the picking waves page showing the picking wave and the sales orders and products associated
     */
    public function showPickingWaves()
    {
        $waves = [
            [
                'id' => '2',
                'num_orders' => '2',
                'num_products' => '7',
                'date' => '2019-10-24',
                'orders' => [
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
                ]
            ],
            [
                'id' => '1',
                'num_orders' => '1',
                'num_products' => '9',
                'date' => '2019-10-24',
                'orders' => [
                    [
                        'id' => '8',
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
                        'id' => '6',
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
                ]
            ]
        ];

        return View('manager.pickingWaves', ['waves' => $waves]);
    }

    /**
     * Display the replenishment page with all the existing products
     */
    public function showReplenishment()
    {

        $products =  Products::getProducts();

        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]['stock'] == 0)
                $products[$i]['status'] = 'OUT OF STOCK';
            else if ($products[$i]['stock'] < $products[$i]['min_stock'])
                $products[$i]['status'] = 'LAST UNITS';
            else
                $products[$i]['status'] = 'ALL GOOD';
        }

        return View('manager.replenishment', ['products' => $products]);
    }

    /**
     * Create a Picking Wave receiving only the sales orders ids
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function createPickingWave(Request $request)
    {
        $salesOrders = SalesOrdersController::saleOrderById(explode(',', $request->input('ids')));

        $pickingWaveId = PickingWaves::insertWave();

        foreach ($salesOrders as $saleOrder) {
            $saleOrder['picking_wave_id'] = $pickingWaveId;
            SalesOrders::insertSaleOrder($saleOrder);

            foreach ($saleOrder['items'] as $item) {
                $item['picking_wave_id'] = $pickingWaveId;
                PickingWavesState::updatePickingWaveState($item);
            }
        }

        return response('', 200, []);
    }
}
