<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Inserts a product
     *
     * @param array $array
     */
    public static function insertProduct(array $array) {
        $product = new Products();
        $product->id = $array['id'];
        $product->stock = $array['stock'];
        $product->warehouse_section = $array['warehouse_section'];
        $product->save();
    }
}
