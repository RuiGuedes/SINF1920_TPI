<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickingWaves extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'picking_waves';

    /**
     * @param int $num_orders
     * @return int
     */
    public static function insertWave(int $num_orders): int
    {
        $pickingWave = new PickingWaves();
        $pickingWave->num_orders = $num_orders;
        $pickingWave->save();
        return $pickingWave->id;
    }

    /**
     * @return mixed
     */
    public static function getOrderedWaves()
    {
        return self::where('user_id', null)->orderby('created_at', 'asc')->get();
    }

    public static function getUserPickingWave(String $user_id)
    {
        if(self::where('user_id', $user_id)->exists())
            return self::select('picking_waves.id','picking_waves.user_id','picking_waves.num_orders','picking_waves.created_at')
                ->leftJoin('packing', 'packing.picking_wave_id', '=', 'picking_waves.id')
                ->where([['picking_waves.user_id', $user_id], ['packing.id', null]])
                ->first();

        return null;
    }

    /**
     * @param $wave_id
     * @param $user_id
     */
    public static function assignToUser(String $wave_id, String $user_id)
    {
        $pickingWave = self::find($wave_id);
        $pickingWave->user_id = $user_id;
        $pickingWave->save();
    }

    public static function checkIfWavesCompleted(String $wave_id)
    {
        return Packing::where('picking_wave_id', $wave_id)->exists();
    }
}
