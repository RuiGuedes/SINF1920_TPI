<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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

        $dispatchOrders = Dispatching::all();

        foreach ($dispatchOrders as $dispatchOrder) {
            $so = SalesOrders::find($dispatchOrder->sales_order_id);

            array_push($res, [
               'id' => $so->id,
               'owner' => $so->client,
               'date' => $so->date
            ]);
        }

        return $res;
    }
}
