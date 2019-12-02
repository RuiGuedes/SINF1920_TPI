<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'stock', 'warehouse_section'
    ];

    /**
     * Inserts a product
     *
     * @param array $array
     */
    public static function insertProduct(array $array) {
        $product = new Products();
        $product->product_id = $array['product_id'];
        $product->description = $array['description'];
        $product->stock = $array['stock'];
        $product->warehouse_section = $array['warehouse_section'];
        $product->save();
    }

    /**
     * Retrieves all products
     *
     * @return Products[]|Collection
     */
    public static function getProducts() {
        return Products::orderBy('stock')->get();
    }
}
