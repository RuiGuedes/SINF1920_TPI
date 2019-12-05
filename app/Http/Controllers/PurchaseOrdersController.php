<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;
use App\ProductSuppliers;
use Exception;
use Illuminate\Http\Request;

class PurchaseOrdersController extends Controller
{
    /**
     * Retrieves all active purchase orders properly ordered
     *
     * @return array|string
     */
    public static function allPurchaseOrders()
    {
        try {
            $result = JasminConnect::callJasmin('/purchases/orders');
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $purchaseOrders = array();

        foreach(json_decode($result->getBody(), true) as $order) {
            if($order['documentStatus'] === 1) {
                $purchaseOrder = [
                    'sort_key' => substr($order['naturalKey'], '9'),
                    'id' => str_replace('.', '-', $order['naturalKey']),
                    'owner' => $order['sellerSupplierParty'],
                    'date' => substr($order['documentDate'], 0, 10),
                    'items' => []
                ];

                foreach ($order['documentLines'] as $product) {
                    array_push($purchaseOrder['items'], [
                        'id' => $product['purchasesItem'],
                        'description' => $product['description'],
                        'quantity' => $product['quantity'],
                        'stock' => Products::getProductStock($product['purchasesItem']),
                        'zone' => Products::getProductWarehouseSection($product['purchasesItem'])
                    ]);
                }

                array_push($purchaseOrders, $purchaseOrder);
            }
        }

        // Array sorting in ascending order
        usort($purchaseOrders, function ($a, $b) {return $a['sort_key'] > $b['sort_key'];});

        return $purchaseOrders;
    }

    /**
     * Create purchase orders selecting automatically the supplier
     *
     * @param Request $request
     * @return false|string
     */
    public function createPurchaseOrder(Request $request)
    {
        $data = $request->input();

        $suppliers = array();

        foreach ($data as $key => $value) {
            $bestSupplier = ProductSuppliers::getBestSupplierForProduct($key);

            if(array_key_exists($bestSupplier['entity'], $suppliers))
                array_push($suppliers[$bestSupplier['entity']], $bestSupplier);
            else
                $suppliers[$bestSupplier['entity']] = [$bestSupplier];
        }

        foreach($suppliers as $supplier) {
            $documentLines = [];

            for($i = 0; $i < count($supplier); $i++) {
                $product = [
                    'description' => $supplier[$i]['description'],
                    'quantity' => $data[$supplier[$i]['product']],
                    'unitPrice' => number_format(floatval($supplier[$i]['price']), 2),
                    'deliveryDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))),
                    'unit' => 'UN',
                    'itemTaxSchema' => 'ISENTO',
                    'purchasesItem' => $supplier[$i]['product'],
                    'documentLineStatus' => 'OPEN'
                ];

                array_push($documentLines, $product);
            }

            try {
                $result = JasminConnect::callJasmin('/purchases/orders', '', 'GET');
            } catch (Exception $e) {
                return $e->getMessage();
            }

            $seriesNumber = count(json_decode($result->getBody(), true)) + 1;

            try {
                $body = [
                    'documentType' => 'ECF',
                    'company' => 'TP-INDUSTRIES',
                    'serie' => '2019',
                    'seriesNumber' => $seriesNumber,
                    'documentDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))),
                    'postingDate' => date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))),
                    'SellerSupplierParty' => $supplier[0]['entity'],
                    'SellerSupplierPartyName' => $supplier[0]['name'],
                    'accountingParty' => $supplier[0]['entity'],
                    'exchangeRate' => 1,
                    'discount' => 0,
                    'loadingCountry' => $supplier[0]['country'],
                    'unloadingCountry' => 'PT',
                    'currency' => 'EUR',
                    'paymentMethod' => 'NUM',
                    'paymentTerm' => '01',
                    'documentLines' => $documentLines
                ];

                JasminConnect::callJasmin('/purchases/orders', '', 'POST', $body);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        return $data;
    }

    /**
     * Allocates purchase order and automatically updates stock
     *
     * @param Request $request
     * @return array
     */
    public function allocatePurchaseOrder(Request $request)
    {
        $data = explode(',', $request->input('purchase_orders'));

        for($i = 0; $i < count($data); $i++) {
            $order = str_replace('-', '.', $data[$i]);

            // Allocation needed steps (goods and invoice receipts)
            $this->generateGoodsReceipt($order);
            $this->generateInvoiceReceipt($order);
        }

        // Automatically update stock
        ProductsController::updateProductsStock();

        return $data;

    }

    /**
     * Generates goods receipt for a specific purchase order
     *
     * @param String $purchaseOrderID
     * @return void
     */
    public function generateGoodsReceipt(String $purchaseOrderID) {
        try {
            $result = JasminConnect::callJasmin('/goodsReceipt/processOrders/1/1000?company=TP-INDUSTRIES', '', 'GET');
        } catch (Exception $e) {
            return;
        }

        $goodsReceipt = array();

        foreach(json_decode($result->getBody(), true) as $goodReceipt) {
            if($goodReceipt['sourceDocKey'] === $purchaseOrderID)
                array_push($goodsReceipt, $goodReceipt);
        }

        foreach($goodsReceipt as $goodReceipt) {
            $body = [
                [
                    'sourceDocKey' => $goodReceipt['sourceDocKey'],
                    'sourceDocLineNumber' => $goodReceipt['sourceDocLineNumber'],
                    'quantity' => $goodReceipt['quantity'],
                    'selected' => true
                ]
            ];

            try {
                JasminConnect::callJasmin('/goodsReceipt/processOrders/TP-INDUSTRIES', '', 'POST', $body);
            } catch (Exception $e) {
                return;
            }
        }
    }

    /**
     * Generates invoice receipt for a specific purchase order
     *
     * @param String $purchaseOrderID
     * @return void
     */
    public function generateInvoiceReceipt(String $purchaseOrderID) {
        try {
            $result = JasminConnect::callJasmin('/invoiceReceipt/processOrders/1/1000', '', 'GET');
        } catch (Exception $e) {
            return;
        }

        $invoicesReceipt = array();

        foreach(json_decode($result->getBody(), true) as $invoiceReceipt) {
            if($invoiceReceipt['orderKey'] === $purchaseOrderID)
                array_push($invoicesReceipt, $invoiceReceipt);
        }

        foreach($invoicesReceipt as $invoiceReceipt) {
            $body = [
                [
                    'goodsReceiptNoteKey' => $invoiceReceipt['goodsReceiptNoteKey'],
                    'goodsReceiptNoteLineNumber' => $invoiceReceipt['goodsReceiptNoteLineNumber'],
                    'orderKey' => $invoiceReceipt['orderKey'],
                    'orderLineNumber' => $invoiceReceipt['orderLineNumber']
                ]
            ];

            try {
                JasminConnect::callJasmin('/invoiceReceipt/processOrders/TP-INDUSTRIES', '', 'POST', $body);
            } catch (Exception $e) {
                return;
            }
        }
    }
}
