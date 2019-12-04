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
     * @return int
     */
    public static function insertWave(): int
    {
        $pickingWave = new PickingWaves();
        $pickingWave->save();
        return $pickingWave->id;
    }
}
