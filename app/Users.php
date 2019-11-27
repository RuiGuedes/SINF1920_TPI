<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'manager',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Inserts a new manager
     *
     * @param array $array
     */
    public static function insertManager(array $array) {
        $user = new Users();

        $user->username = $array["username"];
        $user->email = $array["email"];
        $user->email_verified_at = $array["email_verified_at"];
        $user->manager = $array["manager"];
        $user->password = $array["password"];
        $user->remember_token = $array["remember_token"];

        $user->save();
    }

}
