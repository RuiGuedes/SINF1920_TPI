<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Dispatching extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dispatching';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get the sales orders that weren't dispatched yet.
     *
     * @return array
     */
    public static function undispatched() {
        $res = [];

        $orders = DB::table('dispatching')
            ->join('sales_orders', 'sales_order_id', '=', 'sales_orders.id')
            ->orderby('date', 'asc')
            ->get();

        foreach ($orders as $order) {
            array_push($res, [
                'id' => $order->id,
                'owner' => $order->client,
                'date' => $order->date
            ]);
        }
        
        return $res;

    }

    public static function insertDispatch($sale_order_id)
    {
        $dispatch = new Dispatching();
        $dispatch->sales_order_id = $sale_order_id;
        $dispatch->save();
    }
}
