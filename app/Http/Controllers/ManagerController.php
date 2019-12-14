<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Data\DataPurchaseOrders;
use App\Http\Controllers\Data\DataReplenishment;
use App\Http\Controllers\Data\DataSalesOrders;
use App\Http\Controllers\Data\DataWave;
use App\JasminToken;
use App\User;
use Illuminate\View\View;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Retrieves sales orders view
     *
     * @return View
     */
    public function showSalesOrders()
    {

        abort_if(!User::isManager(auth()->id()), 403);
        return View('manager.salesOrders', ['sales' => DataSalesOrders::allSalesOrders()]);
    }

    /**
     * Retrieves purchase orders view
     *
     * @return View
     */
    public function showPurchaseOrders()
    {
        abort_if(!User::isManager(auth()->id()), 403);
        return View('manager.purchaseOrders', ['purchases' => DataPurchaseOrders::allPurchaseOrders()]);
    }

    /**
     * Retrieves picking waves view
     *
     * @return View
     */
    public function showPickingWaves()
    {
        abort_if(!User::isManager(auth()->id()), 403);
        return View('manager.pickingWaves', ['waves' => DataWave::allPickingWaves()]);
    }

    /**
     * Retrieves replenishment view
     *
     * @return View
     */
    public function showReplenishment()
    {
        abort_if(!User::isManager(auth()->id()), 403);
        return View('manager.replenishment', ['products' => DataReplenishment::getAllInventory()]);
    }
}
