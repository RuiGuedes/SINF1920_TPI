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

    public static function getSalesOrderId(String $serieId) {

        return self::where('serie_id', $serieId)->first()['id'];
    }
}
