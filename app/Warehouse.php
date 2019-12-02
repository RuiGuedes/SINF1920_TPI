<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'section';

    /**
     * Inserts a warehouse sections
     *
     * @param array $array
     */
    public static function insertWarehouseSections(array $array) {
        for($i = 0; $i < count($array); $i++) {
            $warehouseEntry = new Warehouse();
            $warehouseEntry->section = $array[$i]['section'];
            $warehouseEntry->save();
        }
    }

}
