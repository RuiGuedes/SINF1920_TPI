<?php

namespace App\Http\Controllers\Data;

use App\Products;

class DataReplenishment
{
    /**
     * Retrieves all inventory and their associated status
     *
     * @return array|string
     */
    public static function getAllInventory()
    {
        $products =  Products::getProducts();

        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]['stock'] == 0)
                $products[$i]['status'] = 'OUT OF STOCK';
            else if ($products[$i]['stock'] < $products[$i]['min_stock'])
                $products[$i]['status'] = 'LAST UNITS';
            else
                $products[$i]['status'] = 'ALL GOOD';
        }

        return $products;
    }

}
