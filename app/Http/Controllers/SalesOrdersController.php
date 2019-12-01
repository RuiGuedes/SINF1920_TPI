<?php

namespace App\Http\Controllers;

use App\JasminToken;
use App\SalesOrders;
use App\Http\Middleware\JasminConnect;

class SalesOrdersController extends Controller
{

    public function allSalesOrders() {

        try {
            $result = JasminConnect::callJasmin('sales/orders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        echo $result;

        $salesOrders = json_decode($result->getBody(), true);
        $added = false;

        foreach ($salesOrders as $saleOrder) {
            $order = SalesOrders::getSalesOrderId($saleOrder['serieId']);
            if (empty($order)) {
                try {
                    $newSaleOrder = new SalesOrders();
                    $newSaleOrder->create(['serie_id' => $saleOrder['serieId']]);
                } catch (Exception $e) {
                    return  $e;
                }
                $added = true;
            }
        }

        return $added ? response()->json("New sales orders added!", 200) : response()->json("Nothing to add!", 200);
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
