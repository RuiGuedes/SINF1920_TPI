<?php

namespace App\Http\Controllers;

use App\Dispatching;
use App\Http\Controllers\Data\DataPacking;
use App\Http\Controllers\Data\DataSalesOrders;
use App\Http\Controllers\Data\DataWave;
use App\Packing;
use App\PickingWaves;
use App\SalesOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClerkController extends Controller
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

    public function showPickingWaves()
    {
        return View('clerk.pickingWaves', ['waves' => DataWave::allWorkerPickingWaves()]);
    }

    /**
     * Retrieves picking wave route view
     *
     * @param $id_wave
     * @return View
     */
    public function showPickingRoute($id_wave)
    {
        // Abort if the route is already completed
        abort_if(PickingWaves::checkIfWavesCompleted($id_wave), 403);

        return View('clerk.pickingRoute', [ 'zones_list' => DataWave::pickingRoute($id_wave)]);
    }

    /**
     * Retrieves packing waves view
     *
     * @return View
     */
    public function showPackingWaves()
    {
        return View('clerk.packingWaves', ['waves' => DataPacking::allWorkerPackingWaves()]);
    }

    /**
     * Retrieves packing wave information view
     *
     * @param $packing_id
     * @return View
     */
    public function showPacking($packing_id)
    {
        // Abort if the route is already completed
        abort_if(Packing::checkIfWavesCompleted($packing_id), 403);

        return View('clerk.packing', ['orders' => DataPacking::getPackingOrders($packing_id)]);
    }

    public function showDispatchOrders()
    {
        return View('clerk.dispatching', ['orders' => Dispatching::undispatched()]);
    }

    /**
     * Dispatches a sales order.
     *
     * @param Request $request
     * @return array|string
     */
    public function dispatchOrder(Request $request) {
        try {
            DataSalesOrders::processOrders($request->ids);
        } catch (\Exception $e) {
            return json_encode($e);
        }

        return response('', 200, []);
    }
}
