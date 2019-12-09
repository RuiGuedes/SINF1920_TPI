<?php

namespace App\Http\Controllers\Data;

use App\Dispatching;
use App\Http\Middleware\JasminConnect;
use App\Packing;
use App\PickingWavesState;
use App\Products;
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

    public static function packOrders(Request $request, $packing_id)
    {
        $orders_ids = explode(',', $request->input('data'));

        foreach ($orders_ids as $order_id) {
            self::generateDeliveryNote(str_replace('-', '.', $order_id));
            Dispatching::insertDispatch($order_id);
        }

        return $orders_ids;
    }

    public static function generateDeliveryNote($sale_order_id)
    {
        try {
            $result = JasminConnect::callJasmin('/shipping/processOrders/1/1000?company=TP-INDUSTRIES', '', 'GET');
        } catch (Exception $e) {
            return;
        }

        $deliveryNotes = array();

        foreach(json_decode($result->getBody(), true) as $deliveryNote) {
            if($deliveryNote['sourceDocKey'] === $sale_order_id)
                array_push($deliveryNotes, $deliveryNote);
        }

        foreach($deliveryNotes as $deliveryNote) {
            $body = [
                [
                    'sourceDocKey' => $deliveryNote['sourceDocKey'],
                    'sourceDocLineNumber' => $deliveryNote['sourceDocLineNumber'],
                    'quantity' => $deliveryNote['quantity'],
                    'selected' => true
                ]
            ];

            try {
                JasminConnect::callJasmin('/shipping/processOrders/TP-INDUSTRIES', '', 'POST', $body);
            } catch (Exception $e) {
                return;
            }
        }
    }

    public static function removeOrders(Request $request, $packing_id)
    {
        $orders_ids = explode(',', $request->input('orders_id'));
        $products = explode(',', $request->input('products'));

        for ($i = 0 ; $i < count($products) ; $i += 2) {
            Products::updateStock($products[$i], Products::getProductStock($products[$i]) + intval($products[$i+1]));
        }

        SalesOrders::destroy($orders_ids);

        return $orders_ids;
    }
}
