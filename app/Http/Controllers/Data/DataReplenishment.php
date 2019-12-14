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
        $collection = Products::getProducts();
        $products = [];

        for ($i = 0; $i < count($collection); $i++) {
            if ($collection[$i]['stock'] == 0)
                $collection[$i]['status'] = 'OUT OF STOCK';
            else if ($collection[$i]['stock'] < $collection[$i]['min_stock'])
                $collection[$i]['status'] = 'LAST UNITS';
            else
                $collection[$i]['status'] = 'ALL GOOD';

            array_push($products, [
                'product_id' => $collection[$i]['product_id'],
                'description' => $collection[$i]['description'],
                'min_stock' => $collection[$i]['min_stock'],
                'max_stock' => $collection[$i]['max_stock'],
                'stock' => $collection[$i]['stock'],
                'warehouse_section' => $collection[$i]['warehouse_section'],
                'status' => $collection[$i]['status']
            ]);
        }

        $status = array_column($products, 'status');
        array_multisort($status, SORT_DESC, $products);

        return $products;
    }

}
