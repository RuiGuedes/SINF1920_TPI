<?php

namespace App\Http\Controllers\Data;

use App\Http\Middleware\JasminConnect;
use App\Products;
use App\SalesOrders;
use Exception;
use Illuminate\Support\Facades\DB;

class DataSalesOrders
{
    /**
     * The index of the page of the list that should be returned.
     */
    const PAGE_INDEX = 1;

    /**
     * The number of page elements that should be returned (max: 1000).
     */
    const PAGE_SIZE = 1000;

    /**
     * Jasmin company key.
     */
    const COMPANY_KEY = "TP-INDUSTRIES";

    /**
     *  Jasmin endpoint for open sales orders.
     */
    const ENDPOINT_OPEN_SALES_ORDERS = "/billing/processOrders/". self::PAGE_INDEX . "/" . self::PAGE_SIZE;

    /**
     * Jasmin endpoint for sales orders.
     */
    const ENDPOINT_SALES_ORDERS = "/sales/orders";

    /**
     * Jasmin endpoint for processing sales orders.
     */
    const ENDPOINT_PROCESS_SALES_ORDERS = "/billing/processOrders/". self::COMPANY_KEY;

    /**
     * Retrieves all active sales orders properly ordered
     *
     * @return array|string
     */
    public static function allSalesOrders()
    {
        try {
            $result = JasminConnect::callJasmin('/sales/orders');
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $salesOrders = [];

        foreach (json_decode($result->getBody(), true) as $order) {
            if (!($order['documentStatus'] === 2 || ($order['serie'] === "2019" && $order['seriesNumber'] === 1))) {
                $salesOrder = [
                    'sort_key' => substr($order['naturalKey'], '9'),
                    'id' => str_replace('.', '-', $order['naturalKey']),
                    'owner' => $order['buyerCustomerParty'],
                    'date' => substr($order['documentDate'], 0, 10)
                ];

                $products = [];

                foreach ($order['documentLines'] as $line) {
                    $product = Products::getProductByID($line['salesItem']);

                    array_push($products, [
                        'id' => $line['salesItem'],
                        'description' => $line['description'],
                        'quantity' => $line['quantity'],
                        'zone' => $product->warehouse_section,
                        'stock' => $product->stock
                    ]);
                }

                $salesOrder['items'] = $products;

                array_push($salesOrders, $salesOrder);
            }
        }

        // Array sorting in ascending order
        usort($salesOrders, function ($a, $b) {return $a['sort_key'] > $b['sort_key'];});
        
        // Remove sales orders already in existing picking waves
        for ($i = 0; $i < count($salesOrders); $i++) {
            if (SalesOrders::getExists($salesOrders[$i]['id'])) {
                array_splice($salesOrders, $i, 1);
                $i--;
            }
        }

        return $salesOrders;
    }

    /**
     * Retrieves information about specific sales orders
     *
     * @param $ordersId array
     * @return array|string
     */
    public static function salesOrderById($ordersId)
    {
        $orders = [];

        foreach ($ordersId as $id) {
            $info = explode('-', $id);
            $companyKey = 'TP-INDUSTRIES';

            try {
                $result = JasminConnect::callJasmin(
                    '/sales/orders/' . $companyKey . '/' . $info[0] . '/' . $info[1] . '/' . $info[2]
                );
            } catch (Exception $e) {
                return $e->getMessage();
            }

            $saleOrder = json_decode($result->getBody(), true);

            $documentLines = $saleOrder['documentLines'];
            $products = [];

            foreach ($documentLines as $line) {
                $product = Products::getProductByID($line['salesItem']);

                array_push($products, [
                        'id' => $line['salesItem'],
                        'description' => $product->description,
                        'zone' => $product->warehouse_section,
                        'stock' => $product->stock,
                        'quantity' => $line['quantity']
                    ]);
            }

            array_push($orders, [
                    'id' => $id,
                    'owner' => $saleOrder['buyerCustomerParty'],
                    'date' => explode('T', $saleOrder['documentDate'])[0],
                    'items' => $products
                ]);
        }

        return $orders;
    }

    /**
     * Retrieves all the sales orders that can be processed by the 'Invoices' service.
     *
     * @return array
     * @throws Exception
     */
    public static function openOrders()
    {
        $billingOrderLines = self::getBillingOrderLines();
        $salesOrders = self::all();
        $res = [];

        foreach ($billingOrderLines as $key => $billingOrderLine) {
            $res[$key] = [
                'salesOrder' => $salesOrders[$key],
                'billingOrderLines' => $billingOrderLine
            ];
        }

        return $res;
    }

    /**
     * Return the sales orders with the ids provided, that don't have invoices.
     *
     * @param $ids
     * @return array
     * @throws Exception
     */
    private static function openOrdersByIds($ids) {
        $orders = self::openOrders();
        $res = [];

        foreach ($orders as $order) {
            $id = $order['salesOrder']->naturalKey;

            if (in_array($id, $ids)) {
                $res[$id] = $order;
            }
        }

        return $res;
    }

    /**
     * Return the billing order lines associated with sales orders that don't have invoices.
     *
     * @return array
     * @throws Exception
     */
    private static function getBillingOrderLines() {
        try {
            $request = JasminConnect::callJasmin(self::ENDPOINT_OPEN_SALES_ORDERS);
        } catch (Exception $e) {
            throw $e;
        }

        $billingOrderLines = json_decode($request->getBody());
        $res = [];

        foreach ($billingOrderLines as $billingOrderLine) {
            $id = $billingOrderLine->orderKey;

            if (array_key_exists($id, $res)) {
                array_push($res[$id], $billingOrderLine);
            } else {
                $res[$id] = [$billingOrderLine];
            }
        }

        return $res;
    }

    /**
     * Returns all the sales orders, even those that are not allocated to a picking wave.
     *
     * @return array
     * @throws Exception
     */
    public static function all() {
        try {
            $request = JasminConnect::callJasmin(self::ENDPOINT_SALES_ORDERS);
        } catch (Exception $e) {
            throw $e;
        }

        $orders = json_decode($request->getBody());
        $res = [];

        foreach ($orders as $order) {
            $res[$order->naturalKey] = $order;
        }

        return $res;
    }

    /**
     * Create an invoice for a single sales order.
     *
     * @param $order
     * @throws Exception
     */
    private static function processSingleOrder($order) {
        $endpoint = self::ENDPOINT_PROCESS_SALES_ORDERS;
        $billingOrderLines = $order['billingOrderLines'];

        $body = [];

        foreach ($billingOrderLines as $billingOrderLine) {
            array_push($body, [
                'orderKey' => $order['salesOrder']->naturalKey,
                'orderLineNumber' => $billingOrderLine->orderLineNumber,
                'deliveryKey' => $billingOrderLine->deliveryKey,
                'quantity' => $billingOrderLine->quantity,
                'deliveryLineNumber' => $billingOrderLine->deliveryLineNumber
            ]);
        }

        try {
            JasminConnect::callJasmin($endpoint, '', 'POST', $body);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create an invoice for the given sales orders ids.
     *
     * @param $ordersIds
     * @throws Exception
     */
    public static function processOrders($ordersIds) {
        try {
            $openOrders = self::openOrdersByIds($ordersIds);
        } catch (Exception $e) {
            throw $e;
        }

        if ($openOrders === []) return;

        foreach ($ordersIds as $id) {
            $order = $openOrders[$id];
            try {
                self::processSingleOrder($order);
            } catch (Exception $e) {
                throw $e;
            }

            DB::table('dispatching')->where('sales_order_id', $id)->delete();
        }
    }
}
