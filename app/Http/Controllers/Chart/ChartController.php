<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\server\PushServ;
use GatewayClient\Gateway;

class ChartController extends Controller
{
    //
    public static function singleChart($client_id, array $param)
    {
        if (Gateway::isUidOnline($param['to_user_id'])){
            Gateway::sendToUid($param['to_user_id'], $param['message']);
            self::apiReturn($client_id,'send success',1);
        }else{
            PushServ::offlineMsg('user_id',$param['to_user_id'],$param['message']);
            self::apiReturn($client_id,'send success',1);
        }
    }

    public static function apiReturn($client_id, $msg = '', $code = 0){
        $msgData = json_encode([
            "content" => [],
            "info"    => $msg,
            "code"    => $code,
            "type"    => 'json',
        ], true);
        Gateway::sendToClient($client_id, $msgData);
    }
}
