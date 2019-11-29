<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JasminToken extends Model
{
    protected $fillable = ['access_token', 'expires_in', 'token_type'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jasmin_tokens';

    /**
     * Adds new token to the database
     *
     * @param string $token
     * @param string $type
     * @param int $expiresIn
     */
    public static function addNewToken(string $token, string $type, int $expiresIn) {
       $jasminToken = new JasminToken();
       $jasminToken->access_token = $token;
       $jasminToken->token_type = $type;
       $jasminToken->expires_in = $expiresIn;

       $jasminToken->save();
    }

    /**
     * Retrieves the active token
     * @return mixed
     */
    public static function getActiveToken() {
        $activeToken = self::where('expires_in', '>', 60)->first();

        if (empty($activeToken)) {
            self::updateExpiryDate();
        }

        return $activeToken;
    }

    /**
     * Updates token expiration date
     */
    public static function updateExpiryDate() {
        $activeToken = self::where('expires_in', '>', 0)->first();
        if (empty($activeToken)) {
            return;
        }

        if ($activeToken['expires_in'] <= 60) {
            self::where('id', $activeToken['id'])
                ->update(['expires_in' => 0]);
            return;
        }

        $updatedAt = strtotime($activeToken['updated_at']);
        $expiryDate = $updatedAt + $activeToken['expires_in'];
        $timeNow = time();

        self::where('id', $activeToken['id'])
            ->update(['expires_in' => $expiryDate-$timeNow]);
    }
}
