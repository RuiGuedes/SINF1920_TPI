<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatching extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dispatching';

    public static function undispatched() {
        return [
                   [
                       'id' => '4',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ],
                   [
                       'id' => '7',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ],
                   [
                       'id' => '8',
                       'order_id' => 'ay3s678-8df8d9-cvk2kfd4',
                       'owner' => 'C0004',
                       'date' => '2019-07-24',
                   ]
               ];
    }
}
