<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrders extends Model
{
    use SoftDeletes;

    protected $fillable = ['serie_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_orders';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @param $saleOrder
     */
    public static function insertSaleOrder($saleOrder)
    {
        $sale = new SalesOrders();
        $sale->id = $saleOrder['id'];
        $sale->picking_wave_id = $saleOrder['picking_wave_id'];
        $sale->client = $saleOrder['owner'];
        $sale->date = $saleOrder['date'];
        $sale->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getExists($id)
    {
        return SalesOrders::where('id', $id)->exists();
    }

    public static function getSalesOrdersIdsByWaveId($waveId)
    {
        $salesIds = self::select('id')->where('picking_wave_id', $waveId)->get();
        $ids = [];

        foreach ($salesIds as $saleId) array_push($ids, $saleId['id']);

        return $ids;
    }
}
