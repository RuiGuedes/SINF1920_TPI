<?php

namespace App\Http\Controllers\Data;

use App\Packing;
use App\PickingWavesState;
use App\SalesOrders;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPacking
{
    /**
     * Retrieves all packing Waves properly ordered and formatted for worker view
     *
     * @return array
     */
    public static function allWorkerPackingWaves()
    {
        $packingWaves = Packing::getOrderedPackingWaves();
        $waves = [];

        foreach ($packingWaves as &$packingWave) {
            $date = null;

            try {
                $date = new DateTime($packingWave->created_at);
                $date = $date->format('Y-m-d');
            } catch (\Exception $e) {
                $e->getMessage();
            }

            array_push($waves, [
                'id' => $packingWave->id,
                'num_orders' => $packingWave->num_orders,
                'num_products' => count(PickingWavesState::getPickingWaveStatesByWaveId($packingWave->picking_wave_id)),
                'date' => $date
            ]);
        }

        return $waves;
    }

    /**
     * @param $packing_id
     * @return array|string
     */
    public static function getPackingOrders($packing_id)
    {
        Packing::assignToUser($packing_id, Auth::user()->getAuthIdentifier());

        $packing = Packing::getPackingById($packing_id);
        $sales_orders = DataSalesOrders::salesOrderById(SalesOrders::getSalesOrdersIdsByWaveId($packing->picking_wave_id));
        $products_state = PickingWavesState::getPickingWaveStatesByWaveId($packing->picking_wave_id);

        foreach ($products_state as &$state) {
            foreach ($sales_orders as &$sale_order) {
                foreach ($sale_order['items'] as &$item) {
                    if ($state->product_id == $item['id']) {
                        if ($state->picked_qnt >= $item['quantity']) {
                            $item['picked_qnt'] = $item['quantity'];
                            $state->picked_qnt -= $item['quantity'];
                        } else {
                            $item['picked_qnt'] = $state->picked_qnt;
                            $state->picked_qnt = 0;
                        }
                    }
                }
            }
        }

        return $sales_orders;
    }
}
