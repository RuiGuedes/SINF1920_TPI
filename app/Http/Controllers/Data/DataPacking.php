<?php

namespace App\Http\Controllers\Data;

use App\Packing;
use App\PickingWavesState;
use DateTime;

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
}
