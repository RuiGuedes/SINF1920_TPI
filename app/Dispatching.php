<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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

    public static function insertDispatch($sale_order_id)
    {
        $dispatch = new Dispatching();
        $dispatch->sales_order_id = $sale_order_id;
        $dispatch->save();
    }
}
