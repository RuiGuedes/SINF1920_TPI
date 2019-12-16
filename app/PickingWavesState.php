<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickingWavesState extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'picking_waves_state';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['picking_wave_id', 'product_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'exception' => false,
    ];

    /**
     * Insert a new Picking Wave State
     *
     * @param $item
     */
    public static function insertPickingWaveState($item)
    {
        $pickingWaveState = new PickingWavesState();
        $pickingWaveState->picking_wave_id = $item['picking_wave_id'];
        $pickingWaveState->product_id = $item['id'];
        $pickingWaveState->desired_qnt = $item['quantity'];
        $pickingWaveState->save();
    }

    /**
     * Update the desired quantity
     *
     * @param $item
     */
    public static function updateDesiredQntPickingWaveState($item): void
    {
        $pickingWaveState = PickingWavesState::where([['picking_wave_id', $item['picking_wave_id']], ['product_id', $item['id']]])->first();

        if ($pickingWaveState != null) {
            PickingWavesState::where([['picking_wave_id', $item['picking_wave_id']], ['product_id', $item['id']]])
                ->update(['desired_qnt' => $pickingWaveState->desired_qnt + $item['quantity']]);
        } else {
            self::insertPickingWaveState($item);
        }
    }

    /**
     * Retrieve the picking wave states of a wave
     *
     * @param $waveId
     * @return mixed
     */
    public static function getPickingWaveStatesByWaveId($waveId)
    {
        return self::where('picking_wave_id', $waveId)->get();
    }

    /**
     * @param String $wave_id
     * @param $product_id
     * @param $picked_qnt
     */
    public static function updatePickedQntPickingWaveState(String $wave_id, $product_id, $picked_qnt)
    {
        $pickingWaveState = PickingWavesState::where([['picking_wave_id', $wave_id], ['product_id', $product_id]])->first();

        PickingWavesState::where([['picking_wave_id', $wave_id], ['product_id', $product_id]])
            ->update([
                'picked_qnt' => $picked_qnt,
                'exception' => $pickingWaveState->desired_qnt != $pickingWaveState->picked_qnt
            ]);
    }
}
