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
     * Get the sales order associated with the dispatch.
     *
     * @return HasOne
     */
    public function salesOrder() {
        return $this->hasOne('App\SalesOrders');
    }

    /**
     * Get the sales orders that weren't dispatched yet.
     *
     * @return array
     */
    public static function undispatched() {
        // todo: get active dispatching records, and for each one retrieve the salesOrder id. Return associative array
        // with that info.
        return [
                   [
                       'id' => '4',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ],
                   [
                       'id' => '7',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ],
                   [
                       'id' => '8',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ]
               ];
    }
}
