<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use Facade\FlareClient\View;

class ManagerController extends Controller
{
    /**
     * Display the sales orders page with all the sales orders to treat
     */
    public function showSalesOrders()
    {
        try {
            $result = JasminConnect::callJasmin('/materialsCore/materialsItems');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $products = json_decode($result->getBody(), true);

        echo $products[0]['itemKey'] . "\xA";
        echo substr($products[0]['barcode'], 4) . "\xA";
        echo $products[0]['materialsItemWarehouses'][0]['stockBalance'] . "\xA";


        //var_dump($products);

        for($i = 0; $i < count($products); $i++) {
//            print_r($products[$i]);
        }

        // var_dump($salesOrders);
        return;

        $orders = [
            [
                'id' => '4',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
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
            ],
            [
                'id' => '8',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
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
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
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
            ],
            [
                'id' => '8',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
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
        $products = [
            [
                'id' => '56',
                'description' => 'AK-47',
                'zone' => 'D4',
                'stock' => '9',
                'status' => 'Last Units'
            ],
            [
                'id' => '56',
                'description' => 'AK-47',
                'zone' => 'D4',
                'stock' => '9'
            ],
            [
                'id' => '58',
                'description' => 'AK-48',
                'zone' => 'D4',
                'stock' => '0',
                'status' => 'Out of Stock'
            ],
            [
                'id' => '58',
                'description' => 'Desert Eagle',
                'zone' => 'B3',
                'stock' => '300'
            ]
        ];

        return View('manager.replenishment', ['products' => $products]);
    }
}
