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
use Illuminate\Support\Facades\Auth;

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
            $countProducts = 0;

            foreach ($orders as &$order) {
                $countProducts += count($order['items']);

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
                'num_products' => $countProducts,
                'date' => $date,
                'orders' => $orders
            ]);
        }

        return $waves;
    }

    /**
     * Retrieves all picking Waves properly ordered and formatted for worker view
     *
     * @return array
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
     * Retrieves picking route properly ordered and sectioned
     *
     * @param $waveId
     * @return array
     */
    public static function pickingRoute($waveId): array
    {
        PickingWaves::assignToUser($waveId, Auth::user()->getAuthIdentifier());

        $states = PickingWavesState::getPickingWaveStatesByWaveId($waveId);
        $zone_list = [];

        foreach ($states as $state) {
            $item = Products::getProductByID($state->product_id);
            $product = [
                'product_id' => $state->product_id,
                'section' => $item->warehouse_section,
                'product' => $item->description,
                'quantity' => $state->desired_qnt
            ];

            $zone = $item->warehouse_section[0];
            $section = $item->warehouse_section;

            if (array_key_exists($zone, $zone_list)) {
                if (array_key_exists($section, $zone_list[$zone]['products']))
                    array_push($zone_list[$zone]['products'][$section], $product);
                else
                    $zone_list[$zone]['products'][$section] = [$product];
            } else {
                $zone_list[$zone] = [
                    'zone' => $zone,
                    'products' => [$section => [$product]]
                ];
            }
        }

        ksort($zone_list);
        foreach ($zone_list as &$zone)
            ksort($zone['products']);

        return array_values($zone_list);
    }

    /**
     * Complete a picking wave route and create a packing wave
     *
     * @param Request $request
     * @param $idWave
     */
    public static function completePickingWave(Request $request, $idWave)
    {
        $products = $request->input();

        foreach ($products as $productId => $productInfo) {
            $productInfo = explode(',', $productInfo);

            PickingWavesState::updatePickedQntPickingWaveState($idWave, $productId, $productInfo[0]);

            $newStock = Products::getProductStock($productId) - $productInfo[0];
            if ($productInfo[1] == 2)
                $newStock = 0;

            Products::updateStock($productId, $newStock);
        }

        Packing::insertPackingWave($idWave);
    }
}