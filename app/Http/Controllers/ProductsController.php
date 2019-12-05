<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JasminConnect;
use App\Products;

class ProductsController extends Controller
{
    /**
     * Updates products stock with updated values
     */
    public static function updateProductsStock()
    {
        try {
            $result = JasminConnect::callJasmin('/materialsCore/materialsItems');
        } catch (Exception $e) {
            return;
        }

        foreach(json_decode($result->getBody(), true) as $product) {
            Products::updateStock($product['itemKey'], $product['materialsItemWarehouses'][0]['stockBalance']);
        }
    }

}
