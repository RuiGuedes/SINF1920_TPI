<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrders extends Model
{
    use SoftDeletes;

    protected $fillable = ['sales_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_orders';

    /**
     * Insert a new Sale order
     *
     * @param $saleOrder
     */
    public static function insertSaleOrder($saleOrder)
    {
        $sale = new SalesOrders();
        $sale->sales_id = $saleOrder['id'];
        $sale->picking_wave_id = $saleOrder['picking_wave_id'];
        $sale->client = $saleOrder['owner'];
        $sale->date = $saleOrder['date'];
        $sale->save();
    }

    /**
     * Check if a sales order with id exists in DB
     *
     * @param $id
     * @return mixed
     */
    public static function getExists($id)
    {
        return SalesOrders::where('sales_id', $id)->exists();
    }

    /**
     * Retrieve the sales orders of a picking wave
     *
     * @param $waveId
     * @return array
     */
    public static function getSalesOrdersIdsByWaveId($waveId)
    {
        $salesIds = self::select('sales_id')->where('picking_wave_id', $waveId)->get();
        $ids = [];

        foreach ($salesIds as $saleId) array_push($ids, $saleId['sales_id']);

        return $ids;
    }

    public static function removeSalesOrders($sales_ids)
    {
        $ids = [];

        foreach ($sales_ids as $sales_id) {
            array_push($ids, self::where('sales_id', $sales_id)->first()->id);
        }

        self::destroy($ids);
    }
}
