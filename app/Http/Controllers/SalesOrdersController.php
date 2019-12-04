<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;
use App\SalesOrders;
use Exception;

class SalesOrdersController extends Controller
{
    /**
     * Retrieves all active sales orders properly ordered
     *
     * @return array|string
     */
    public static function allSalesOrders()
    {
        try {
            $result = JasminConnect::callJasmin('/sales/orders');
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $salesOrders = [];

        foreach (json_decode($result->getBody(), true) as $order) {
            if (!($order['documentStatus'] === 2 || ($order['serie'] === "2019" && $order['seriesNumber'] === 1))) {
                $salesOrder = [
                    'id' => str_replace('.', '-', $order['naturalKey']),
                    'owner' => $order['buyerCustomerParty'],
                    'date' => substr($order['documentDate'], 0, 10),
                ];

                $products = [];

                foreach ($order['documentLines'] as $line) {
                    $product = Products::getProductByID($line['salesItem']);

                    array_push($products, [
                        'id' => $line['salesItem'],
                        'description' => $line['description'],
                        'quantity' => $line['quantity'],
                        'zone' => $product->warehouse_section,
                        'stock' => $product->stock
                    ]);
                }

                $salesOrder['items'] = $products;

                array_push($salesOrders, $salesOrder);
            }
        }

        $salesOrders = array_reverse($salesOrders);

        // Remove sales orders already in existing picking waves
        for ($i = 0; $i < count($salesOrders); $i++) {
            if (SalesOrders::getExists($salesOrders[$i]['id'])) {
                array_splice($salesOrders, $i, 1);
                $i--;
            }
        }

        return $salesOrders;
    }

    /**
     * Retrieves information about specific sales orders
     *
     * @param $ordersId array
     * @return array|string
     */
    public static function salesOrderById($ordersId)
    {
        $orders = [];

        foreach ($ordersId as $id) {
            $info = explode('-', $id);
            $companyKey = 'TP-INDUSTRIES';

            try {
                $result = JasminConnect::callJasmin(
                    '/sales/orders/' . $companyKey . '/' . $info[0] . '/' . $info[1] . '/' . $info[2]
                );
            } catch (Exception $e) {
                return $e->getMessage();
            }

            $saleOrder = json_decode($result->getBody(), true);

            $documentLines = $saleOrder['documentLines'];
            $products = [];

            foreach ($documentLines as $line) {
                array_push($products, [
                        'id' => $line['salesItem'],
                        'quantity' => $line['quantity']
                    ]);
            }

            array_push($orders, [
                    'id' => $id,
                    'owner' => $saleOrder['buyerCustomerParty'],
                    'date' => explode('T', $saleOrder['documentDate'])[0],
                    'items' => $products
                ]);
        }

        return $orders;
    }
}
