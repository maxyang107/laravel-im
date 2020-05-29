<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $table      = 'chart_user';
    public    $timestamps = false;

    public static function checkUserToken(string $token, string $user_id)
    {
        $auth = self::where(['user_token' => $token, 'user_id' => $user_id, 'status' => 1])->get();
        return $auth;
    }
}
