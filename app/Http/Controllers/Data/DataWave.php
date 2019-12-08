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
//'id' => '1',
//'num_orders' => '1',
//'num_products' => '9',
//'date' => '2019-10-24',