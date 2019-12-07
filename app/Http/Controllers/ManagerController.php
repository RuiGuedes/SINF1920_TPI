<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Data\DataPurchaseOrders;
use App\Http\Controllers\Data\DataReplenishment;
use App\Http\Controllers\Data\DataSalesOrders;
use App\Http\Controllers\Data\DataWave;
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
        return View('manager.salesOrders', ['sales' => DataSalesOrders::allSalesOrders()]);
    }

    /**
     * Retrieves purchase orders view
     *
     * @return View
     */
    public function showPurchaseOrders()
    {
        return View('manager.purchaseOrders', ['purchases' => DataPurchaseOrders::allPurchaseOrders()]);
    }

    /**
     * Retrieves picking waves view
     *
     * @return View
     */
    public function showPickingWaves()
    {
        return View('manager.pickingWaves', ['waves' => DataWave::allPickingWaves()]);
    }

    /**
     * Retrieves replenishment view
     *
     * @return View
     */
    public function showReplenishment()
    {
        return View('manager.replenishment', ['products' => DataReplenishment::getAllInventory()]);
    }
}
