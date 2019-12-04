<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSuppliers extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_suppliers';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['supplier_entity', 'product_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Inserts new product and supplier relation
     *
     * @param array $array
     */
    public static function insertSupplier(array $array) {
        $prodSupplier = new ProductSuppliers();

        $prodSupplier->supplier_entity = $array['entity'];
        $prodSupplier->product_id = $array['item'];
        $prodSupplier->price = $array['price'];

        $prodSupplier->save();
    }

    /**
     * Retrieves the best supplier for a certain product
     *
     * @param string $product_id
     * @return mixed
     */
    public static function getBestSupplierForProduct(string $product_id) {
        return self::select('products.product_id as product', 'suppliers.entity as entity', 'price')
            ->join('suppliers', 'product_suppliers.supplier_entity', '=', 'suppliers.entity')
            ->join('products', 'product_suppliers.product_id', '=', 'products.product_id')
            ->where('products.product_id' , '=', $product_id)
            ->orderBy('price', 'asc')
            ->first();
    }

}
