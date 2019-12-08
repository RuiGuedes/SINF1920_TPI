<?php

namespace App\Http\Controllers\Data;

use App\Packing;
use App\PickingWaves;
use App\PickingWavesState;
use App\Products;
use App\SalesOrders;
use DateTime;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataWave
{
    /**
     * Create a Picking Wave receiving only the sales orders ids
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function createPickingWave(Request $request)
    {
        $salesOrders = DataSalesOrders::salesOrderById(explode(',', $request->input('ids')));

        $pickingWaveId = PickingWaves::insertWave(count($salesOrders));

        foreach ($salesOrders as $saleOrder) {
            $saleOrder['picking_wave_id'] = $pickingWaveId;
            SalesOrders::insertSaleOrder($saleOrder);

            foreach ($saleOrder['items'] as $item) {
                $item['picking_wave_id'] = $pickingWaveId;
                PickingWavesState::updateDesiredQntPickingWaveState($item);
            }
        }

        return response('', 200, []);
    }

    /**
     * Retrieves all picking Waves properly ordered
     *
     * @return array|string
     */
    public static function allPickingWaves()
    {
        $pickingWaves = PickingWaves::getOrderedWaves();
        $waves = [];

        foreach ($pickingWaves as $pickingWave) {
            $orders = DataSalesOrders::salesOrderById(SalesOrders::getSalesOrdersIdsByWaveId($pickingWave->id));
            $count_products = 0;

            foreach ($orders as &$order) {
                $count_products += count($order['items']);

                foreach ($order['items'] as &$item) {
                    $product = Products::getProductByID($item['id']);

                    $item['description'] = $product->description;
                    $item['zone'] = $product->warehouse_section;
                    $item['stock'] = $product->stock;
                }
            }

            $date = null;

            try {
                $date = new DateTime($pickingWave->created_at);
                $date = $date->format('Y-m-d');
            } catch (\Exception $e) {
                $e->getMessage();
            }

            array_push($waves, [
                'id' => $pickingWave->id,
                'num_orders' => $pickingWave->num_orders,
                'num_products' => $count_products,
                'date' => $date,
                'orders' => $orders
            ]);
        }

        return $waves;
    }

    /**
     *
     */
    public static function allWorkerPickingWaves()
    {
        $pickingWaves = PickingWaves::getOrderedWaves();
        $waves = [];

        foreach ($pickingWaves as $pickingWave) {
            $items = [];

            $states = PickingWavesState::getPickingWaveStatesByWaveId($pickingWave->id);

            foreach ($states as $state) {
                $product = Products::getProductByID($state->product_id);

                array_push($items, [
                    'id' => $product->product_id,
                    'description' => $product->description,
                    'zone' => $product->warehouse_section,
                    'quantity' => $state->desired_qnt,
                    'stock' => $product->stock
                ]);
            }

            $date = null;

            try {
                $date = new DateTime($pickingWave->created_at);
                $date = $date->format('Y-m-d');
            } catch (\Exception $e) {
                $e->getMessage();
            }

            array_push($waves, [
                'id' => $pickingWave->id,
                'num_orders' => $pickingWave->num_orders,
                'num_products' => count($items),
                'date' => $date,
                'items' => $items
            ]);
        }

        return $waves;
    }

    /**
     * @param Request $request
     * @param $id_wave
     */
    public static function completePickingWave(Request $request, $id_wave)
    {
        $products = $request->input();

        foreach ($products as $product_id => $product_info) {
            $product_info = explode(',', $product_info);

            PickingWavesState::updatePickedQntPickingWaveState($id_wave, $product_id, $product_info[0]);

            $new_stock = Products::getProductStock($product_id) - $product_info[0];
            if ($product_info[1] == 2)
                $new_stock = 0;

            Products::updateStock($product_id, $new_stock);
        }

        Packing::insertPackingWave($id_wave);
    }
}