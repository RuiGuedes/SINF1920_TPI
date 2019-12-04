<?php

namespace App\Http\Controllers;

use App\PickingWaves;
use App\PickingWavesState;
use App\SalesOrders;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WaveController extends Controller
{
    /**
     * Create a Picking Wave receiving only the sales orders ids
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function createPickingWave(Request $request)
    {
        $salesOrders = SalesOrdersController::salesOrderById(explode(',', $request->input('ids')));

        $pickingWaveId = PickingWaves::insertWave();

        foreach ($salesOrders as $saleOrder) {
            $saleOrder['picking_wave_id'] = $pickingWaveId;
            SalesOrders::insertSaleOrder($saleOrder);

            foreach ($saleOrder['items'] as $item) {
                $item['picking_wave_id'] = $pickingWaveId;
                PickingWavesState::updatePickingWaveState($item);
            }
        }

        return response('', 200, []);
    }
}
