<?php

namespace App\Http\Controllers;

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
     * @param null $status
     * @return View
     */
    public function showPurchaseOrders()
    {
        return View('manager.purchaseOrders', ['purchases' => PurchaseOrdersController::allPurchaseOrders()]);
    }

    /**
     * Retrieves picking waves view
     *
     * @param null $status
     * @return View
     */
    public function showPickingWaves()
    {
            [
        ];

        return View('manager.pickingWaves', ['waves' => WaveController::allPickingWaves()])->withErrors([$errors]);
        return View('manager.pickingWaves', ['waves' => $waves]);
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
