<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;

class SalesOrdersController extends Controller
{
    public static function allSalesOrders()
    {
        try {
            $result = JasminConnect::callJasmin('/sales/orders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $salesOrders = [];

        foreach (json_decode($result->getBody(), true) as $saleOrder) {
            if ($saleOrder['documentStatus'] === 2 || ($saleOrder['serie'] === "2019" && $saleOrder['seriesNumber'] === 1))
                continue;

            $order = [
                'id' => $saleOrder['documentType'] . '-' . $saleOrder['serie'] . "-" . $saleOrder['seriesNumber'],
                'owner' => $saleOrder['buyerCustomerParty'],
                'date' => explode('T', $saleOrder['documentDate'])[0],
            ];

            $products = [];

            foreach ($saleOrder['documentLines'] as $line) {
                $dbProduct = Products::where('product_id', $line['salesItem'])->get()->first();

                array_push($products, [
                        'id' => $line['salesItem'],
                        'description' => $line['description'],
                        'quantity' => $line['quantity'],
                        'zone' => $dbProduct->warehouse_section,
                        'stock' => $dbProduct->stock
                    ]);
            }

            $order['items'] = $products;

            array_push($salesOrders, $order);
        }

        return array_reverse($salesOrders);
    }

    /**
     * @param $ordersId array sales orders id
     * @return array|string
     */
    public static function saleOrderById($ordersId)
    {
        $orders = [];

        foreach ($ordersId as $id) {
            $info = explode('-', $id);
            $companyKey = 'TP-INDUSTRIES';

            try {
                $result = JasminConnect::callJasmin(
                    '/sales/orders/' . $companyKey . '/' . $info[0] . '/' . $info[1] . '/' . $info[2]
                );
            } catch (\Exception $e) {
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
