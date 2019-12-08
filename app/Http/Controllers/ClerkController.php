<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Data\DataSalesOrders;
use App\Http\Controllers\Data\DataWave;
use App\Packing;
use App\PickingWaves;
use App\SalesOrders;
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
        return View('clerk.packingWaves', ['waves' => DataWave::allWorkerPackingWaves()]);
    }

    /**
     * Retrieves packing wave information view
     *
     * @param $packing_id
     * @return View
     */
    public function showPacking($packing_id)
    {
        return View('clerk.packing', ['orders' => DataSalesOrders::salesOrderById(
            SalesOrders::getSalesOrdersIdsByWaveId(Packing::getPackingById($packing_id)->picking_wave_id))]);
    }

    public function showDispatchOrders()
    {
        $orders = [
            [
                'id' => '4',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ],
            [
                'id' => '7',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ],
            [
                'id' => '8',
                'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                'owner' => 'C0004',
                'date' => '2019-07-24',
            ]
        ];

        return View('clerk.dispatching', ['orders' => $orders]);
    }
}
