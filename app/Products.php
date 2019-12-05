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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'min_stock', 'max_stock'
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
        $product->min_stock = $array['min_stock'];
        $product->max_stock = $array['max_stock'];
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

    /**
     * Retrieves specific product stock
     *
     * @param String $product_id
     * @return mixed
     */
    public static function getProductStock(String $product_id) {
        return Products::select('stock')
            ->where('product_id', '=', $product_id)
            ->first()['stock'];
    }

    /**
     * Retrieves specific product warehouse section
     *
     * @param String $product_id
     * @return mixed
     */
    public static function getProductWarehouseSection(String $product_id) {
        return Products::select('warehouse_section')
            ->where('product_id', '=', $product_id)
            ->first()['warehouse_section'];
    }

    /**
     * Retrieves product information through identifier
     *
     * @param String $product_id
     * @return mixed
     */
    public static function getProductByID(String $product_id) {
        return Products::where('product_id', '=', $product_id)->get()->first();
    }

    /**
     * Updates product stock
     *
     * @param String $product_id
     * @param int $stock
     */
    public static function updateStock(String $product_id, int $stock) {
        Products::where('product_id', '=', $product_id)
            ->update(['stock' => $stock]);
    }
}
