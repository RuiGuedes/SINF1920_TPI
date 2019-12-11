<?php

namespace App;

use App\Http\Middleware\JasminConnect;
use Illuminate\Database\Eloquent\Model;

class JasminToken extends Model
{
    protected $fillable = ['access_token', 'token_type'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jasmin_token';

    /**
     * Retrieves the active token
     * @return mixed
     */
    public static function getToken() {
        $activeToken = self::find(1);

        if (empty($activeToken) || ((strtotime(date('Y-m-d H:i:s', time())) - strtotime($activeToken['created_at'])) >= $activeToken['expires_in'])) {
            $new_token = JasminConnect::generateNewToken();
            JasminToken::create(['access_token' => $new_token['access_token'], 'token_type' => $new_token['token_type'], 'expires_in' => $new_token['expires_in']]);
        }
        else
            return $activeToken;

        return self::find(1);
    }

}
