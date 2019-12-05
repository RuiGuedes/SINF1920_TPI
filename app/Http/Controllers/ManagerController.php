<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use Illuminate\View\View;

class ManagerController extends Controller
{
    /**
     * Retrieves sales orders view
     *
     * @return View
     */
    public function showSalesOrders()
    {
        return View('manager.salesOrders', ['sales' => SalesOrdersController::allSalesOrders()]);
    }

    /**
     * Retrieves purchase orders view
     *
     * @param null $status
     * @return View
     */
    public function showPurchaseOrders()
    {
        return View('manager.purchaseOrders', ['purchases' => PurchaseOrdersController::allPurchaseOrders()]);
    }

    /**
     * Retrieves picking waves view
     *
     * @param null $status
     * @return View
     */
    public function showPickingWaves()
    {
//        $body = [
//            [
//                'sourceDocKey' => 'ECF.2019.15',
//                'sourceDocLineNumber' => 1,
//                'quantity' => 200,
//                'selected' => true
//            ]
//        ];
//
//        echo json_encode($body);
//
//        try {
////            $result = JasminConnect::callJasmin('/goodsReceipt/processOrders/1/1000?company=TP-INDUSTRIES', '', 'GET');
//            $result = JasminConnect::callJasmin('/goodsReceipt/processOrders/TP-INDUSTRIES', '', 'POST', $body);
//        } catch (Exception $e) {
//            return $e->getMessage();
//        }
//
//        var_dump($result);
////        var_dump(json_decode($result->getBody(), true)[0]);
//        return;

        $body = [
            [
                'goodsReceiptNoteKey' => 'RM.2019.15',
                'goodsReceiptNoteLineNumber' => 1,
                'orderKey' => 'ECF.2019.15',
                'orderLineNumber' => 1
            ]
        ];

        echo json_encode($body);

        try {
//            $result = JasminConnect::callJasmin('/invoiceReceipt/processOrders/1/1000', '', 'GET');
            $result = JasminConnect::callJasmin('/invoiceReceipt/processOrders/TP-INDUSTRIES', '', 'POST', $body);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        var_dump($result);
//        var_dump(json_decode($result->getBody(), true));
        return;

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
     * Retrieves replenishment view
     *
     * @return View
     */
    public function showReplenishment()
    {
        return View('manager.replenishment', ['products' => ReplenishmentController::getAllInventory()]);
    }
}
