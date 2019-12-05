<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;
use App\PickingWaves;
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
     * @return View
     */
    public function showPurchaseOrders()
    {
        return View('manager.purchaseOrders', ['purchases' => PurchaseOrdersController::allPurchaseOrders()]);
    }

    /**
     * Retrieves picking waves view
     *
     * @return View
     */
    public function showPickingWaves()
    {
        return View('manager.pickingWaves', ['waves' => WaveController::allPickingWaves()]);
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
