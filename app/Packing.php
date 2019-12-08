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
     * Insert a new packing tuple in database
     *
     * @param String $waveId
     */
    public static function insertPackingWave(String $waveId)
    {
        $packing = new Packing();
        $packing->picking_wave_id = $waveId;
        $packing->save();
    }

    /**
     * Retrieve all pending packing waves ordered by created date
     *
     * @return mixed
     */
    public static function getOrderedPackingWaves()
    {
        return self::select('packing.id', 'packing.picking_wave_id', 'picking_waves.num_orders', 'packing.created_at')
            ->where('packing.user_id', null)
            ->join('picking_waves', 'packing.picking_wave_id', '=', 'picking_waves.id')
            ->orderby('packing.created_at', 'asc')
            ->get();
    }
}
