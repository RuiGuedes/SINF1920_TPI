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
     * @return PickingWaves
     */
    public static function insertWave(): PickingWaves
    {
        $pickingWave = new PickingWaves();
        $pickingWave->save();
        return $pickingWave;
    }
}
