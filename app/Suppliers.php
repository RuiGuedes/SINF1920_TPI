<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * Inserts new supplier
     *
     * @param array $array
     */
    public static function insertSupplier(array $array) {
        $supplier = new Suppliers();

        $supplier->entity = $array["entity"];
        $supplier->name = $array["name"];
        $supplier->country = $array["country"];

        $supplier->save();
    }
}
