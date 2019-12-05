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
    public function showPurchaseOrders($status = null)
    {
        $errors = [];

        if ($status == 'added')
            array_push($errors,'New Purchase Orders added');

        return View('manager.purchaseOrders', ['purchases' => PurchaseOrdersController::allPurchaseOrders()])->withErrors([$errors]);
    }

    /**
     * Retrieves picking waves view
     *
     * @param null $status
     * @return View
     */
    public function showPickingWaves($status = null)
    {
        $errors = [];

        if ($status == 'added')
            array_push($errors,'New Picking Wave added');

        return View('manager.pickingWaves', ['waves' => WaveController::allPickingWaves()])->withErrors([$errors]);
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
