<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;
use App\ProductSuppliers;
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
        } catch (\Exception $e) {
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
     *
     *
     * @param Request $request
     * @return false|string
     */
    public function allocatePurchaseOrder(Request $request)
    {
        $data = $request->input();

//        foreach ($data as $key => $value) {
//            return $key . "..." . $value;
//        }

        return $data;

    }
}
