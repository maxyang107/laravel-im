<?php

namespace App\Http\Controllers\Live;

use App\Exceptions\ApiExceptions;
use App\Exceptions\SignalExceptions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\server\PushServ;
use App\Model\User;
use Cassandra\Exception\TruncateException;
use Illuminate\Http\Request;

class Usercontroller extends Controller
{
    //用户鉴权
    public static function checkUserToken(string $token, string $user_id)
    {
        $auth = User::checkUserToken($token, $user_id);
        if ($auth) return true;
        return false;
    }

    public function pushmsg()
    {
        $obj = ['type' => 'all', 'id' => 'clint_id'];
        PushServ::pushSysMsg($obj, 'sys', 1, '操作成功');
    }

}
