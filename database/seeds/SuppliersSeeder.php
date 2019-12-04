<?php

use App\Http\Middleware\JasminConnect;
use App\ProductSuppliers;
use App\Suppliers;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $result = JasminConnect::callJasmin('/purchasesCore/supplierParties');
        } catch (Exception $e) {
            return;
        }

        $suppliersParties = json_decode($result->getBody(), true);
        $data = array();

        for($i = 0; $i < count($suppliersParties); $i++) {
            if(!($suppliersParties[$i]['partyKey'] === 'AT' || $suppliersParties[$i]['partyKey'] === '0001' ||
                $suppliersParties[$i]['partyKey'] === 'SegSocial'))
            {
                $info = ['entity' => $suppliersParties[$i]['partyKey'],
                    'name' => $suppliersParties[$i]['name'],
                    'country' => $suppliersParties[$i]['country'],
                    'items' => array()
                ];

                for($j = 0; $j < count($suppliersParties[$i]['supplierItemPrices']); $j++) {
                    $productInfo = ['item' => $suppliersParties[$i]['supplierItemPrices'][$j]['item'],
                                    'price' => $suppliersParties[$i]['supplierItemPrices'][$j]['price']['amount'],
                                    'entity' => $info['entity']
                    ];
                    array_push($info['items'], $productInfo);
                }
                array_push($data, $info);
            }
        }

        for($i = 0; $i < count($data); $i++) {
            Suppliers::insertSupplier($data[$i]);

            for($j = 0; $j < count($data[$i]['items']); $j++) {
                ProductSuppliers::insertSupplier($data[$i]['items'][$j]);
            }
        }
    }
}
