<?php

namespace App\Http\Controllers;

use App\PickingWaves;
use App\PickingWavesState;
use App\Http\Middleware\JasminConnect;
use App\Products;
use App\ProductSuppliers;
use App\SalesOrders;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

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
        try {
            $result = JasminConnect::callJasmin('/purchases/orders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $purchaseOrders = array();

        foreach(json_decode($result->getBody(), true) as $order) {
            if($order['documentStatus'] === 1) {
                $purchaseOrder = [
                    'sort_key' => substr($order['naturalKey'], '9'),
                    'id' => str_replace('.', '-', $order['naturalKey']),
                    'owner' => $order['sellerSupplierParty'],
                    'date' => substr($order['documentDate'], 0, 10),
                    'items' => []
                ];

                foreach ($order['documentLines'] as $product) {
                    array_push($purchaseOrder['items'], [
                        'id' => $product['purchasesItem'],
                        'description' => $product['description'],
                        'quantity' => $product['quantity'],
                        'stock' => Products::getProductStock($product['purchasesItem']),
                        'zone' => Products::getProductWarehouseSection($product['purchasesItem'])
                    ]);
                }

                array_push($purchaseOrders, $purchaseOrder);
            }
        }

        // Array sorting in ascending order
        usort($purchaseOrders, function ($a, $b) {return $a['sort_key'] > $b['sort_key'];});

        return View('manager.purchaseOrders', ['purchases' => $purchaseOrders]);
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

    /**
     * Create purchase orders selecting automatically the supplier
     *
     * @param Request $request
     * @return false|string
     */
    public function createPurchaseOrder(Request $request)
    {
        $data = $request->input();

        $suppliers = array();

        foreach ($data as $key => $value) {
            $bestSupplier = ProductSuppliers::getBestSupplierForProduct($key);

            if(array_key_exists($bestSupplier['entity'], $suppliers))
                array_push($suppliers[$bestSupplier['entity']], $bestSupplier);
            else
                $suppliers[$bestSupplier['entity']] = [$bestSupplier];
        }

        foreach($suppliers as $supplier) {
            $documentLines = [];

            for($i = 0; $i < count($supplier); $i++) {
                $product = [
                    'description' => $supplier[$i]['description'],
                    'quantity' => $data[$supplier[$i]['product']],
                    'unitPrice' => number_format(floatval($supplier[$i]['price']), 2),
                    'deliveryDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))),
                    'unit' => 'UN',
                    'itemTaxSchema' => 'ISENTO',
                    'purchasesItem' => $supplier[$i]['product'],
                    'documentLineStatus' => 'OPEN'
                ];

                array_push($documentLines, $product);
            }

            try {
                $result = JasminConnect::callJasmin('/purchases/orders', '', 'GET');
            } catch (Exception $e) {
                return $e->getMessage();
            }

            $seriesNumber = count(json_decode($result->getBody(), true)) + 1;

            try {
                $body = [
                    'documentType' => 'ECF',
                    'company' => 'TP-INDUSTRIES',
                    'serie' => '2019',
                    'seriesNumber' => $seriesNumber,
                    'documentDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))),
                    'postingDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))),
                    'SellerSupplierParty' => $supplier[0]['entity'],
                    'SellerSupplierPartyName' => $supplier[0]['name'],
                    'accountingParty' => $supplier[0]['entity'],
                    'exchangeRate' => 1,
                    'discount' => 0,
                    'loadingCountry' => $supplier[0]['country'],
                    'unloadingCountry' => 'PT',
                    'currency' => 'EUR',
                    'paymentMethod' => 'NUM',
                    'paymentTerm' => '01',
                    'documentLines' => $documentLines
                ];

                JasminConnect::callJasmin('/purchases/orders', '', 'POST', $body);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        
        return $data;
    }
}
