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
     * @param $num_orders
     * @return int
     */
    public static function insertWave($num_orders): int
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

    /**
     * @param $wave_id
     * @param $user_id
     */
    public static function assignToUser($wave_id, $user_id)
    {
        $pickingWave = self::find($wave_id);
        $pickingWave->user_id = $user_id;
        $pickingWave->save();
    }
}
