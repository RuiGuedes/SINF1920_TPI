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
     * @return Collection
     */
    public static function undispatched() {
        return DB::table('dispatching')
            ->join('sales_orders', 'sales_order_id', '=', 'sales_orders.id')->get();
    }
}
