<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;

class SalesOrdersController extends Controller
{
    public static function allSalesOrders() {

        try {
            $result = JasminConnect::callJasmin('sales/orders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $salesOrders = json_decode($result->getBody(), true);
        $filteredOrders = [];

        foreach ($salesOrders as $saleOrder) {
            if($saleOrder['documentStatus'] === 2 || ($saleOrder['serie'] === "2019" && $saleOrder['seriesNumber'] === 1))
                continue;
                
            $order = [
                'id' => $saleOrder['documentType'] . $saleOrder['serie'] . "-" . $saleOrder['seriesNumber'],
                'owner' => $saleOrder['buyerCustomerParty'],
                'date' => explode('T', $saleOrder['documentDate'])[0],
            ];

            $documentLines = $saleOrder['documentLines'];
            $products = [];

            foreach ($documentLines as $line) {
                $product = [
                    'id' => $line['salesItem'],
                    'description' => $line['description'],
                    'quantity' => $line['quantity']
                ];

                $dbProduct = Products::where('product_id', $product['id'])->get()->first();
                $product['zone'] = $dbProduct->warehouse_section;
                $product['stock'] = $dbProduct->stock;

                array_push($products, $product);
            }

            $order['items'] = $products;
            
            array_push($filteredOrders, $order);
        }

        return $filteredOrders;
    }

    public function saleOrderBySerieId($serieId)
    {
        try {
            $result = JasminConnect::callJasmin('sales/orders' . '/' . $serieId);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return json_decode($result->getBody(), true);
    }
}
