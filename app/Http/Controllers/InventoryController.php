<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;

class InventoryController extends Controller
{

    public function allStock() {

        try {
            $result = JasminConnect::callJasmin('materialsmanagement/stockTransferOrders');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return json_decode($result->getBody(), true);
    }
}
