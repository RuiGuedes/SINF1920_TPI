<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;

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
            
            $documentId = $saleOrder['documentType'] . $saleOrder['serie'] . "-" . $saleOrder['seriesNumber'];
            $date = explode('T', $saleOrder['documentDate'])[0];
            $owner = $saleOrder['buyerCustomerParty'];
            $documentLines = $saleOrder['documentLines'];
            $products = [];

            foreach ($documentLines as $line) {
                $product = [
                    'id' => $line['salesItem'],
                    'description' => $line['description'],
                    'zone' => null,
                    'quantity' => $line['quantity'],
                    'stock' => null
                ];

                array_push($products, $product);
            }

            $order = [
                'id' => $documentId,
                'owner' => $owner,
                'date' => $date,
                'items' => $products
            ];
            
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
