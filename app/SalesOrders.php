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
}
