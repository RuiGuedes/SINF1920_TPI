<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packing extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packing';

    /**
     * @param String $wave_id
     */
    public static function insertPackingWave(String $wave_id)
    {
        $packing = new Packing();
        $packing->picking_wave_id = $wave_id;
        $packing->save();
        return $packing->id;
    }
}
