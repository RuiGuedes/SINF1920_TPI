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
    }

    public static function getOrderedPackingWaves()
    {
        return self::select('packing.id', 'packing.picking_wave_id', 'picking_waves.num_orders', 'packing.created_at')
            ->where('packing.user_id', null)
            ->join('picking_waves', 'packing.picking_wave_id', '=', 'picking_waves.id')
            ->orderby('packing.created_at', 'asc')
            ->get();
    }
}
