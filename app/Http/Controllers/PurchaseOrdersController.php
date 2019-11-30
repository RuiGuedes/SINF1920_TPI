<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;

class PurchaseOrdersController extends Controller
{
    public function allPurchaseOrders() {

        try {
            $result = JasminConnect::callJasmin('purchases/orders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return json_decode($result->getBody(), true);
    }
}
