<?php

namespace App\Http\Controllers\Dispense;

use App\Http\Controllers\Chart\ChartController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Live\Usercontroller;
use App\Model\OfflineMsg;
use GatewayClient\Gateway;
use Illuminate\Http\Request;

class DistributeController extends Controller
{
    //
    public static function handOut($client_id, string $param)
    {
        $param = json_decode($param, true);
        if (!isset($param['action']) || empty($param['action']))self::apiReturn($client_id,'action is required',0) ;
        switch ($param['action']) {
            case 'login':
                $res = Usercontroller::checkUserToken($param['token'], $param['user_id']);
                if ($res) {
                    self::offlineMsgPush($client_id, $param['user_id']);//用户登录检测是否有离线消息
                    self::apiReturn($client_id,'授权成功',1);
                    Gateway::bindUid($client_id, $param['user_id']);
                }else{
                    self::apiReturn($client_id,'User Token Error!',0);
                }
                break;
            case 'single_chart':
                self::Auth($client_id,$param);
                ChartController::singleChart($client_id, $param);
                break;
            default:
                self::apiReturn($client_id,'Unknown Action!',0);
                break;
        }
    }

    public static function Auth($client_id, array $param)
    {
        $res = Usercontroller::checkUserToken($param['token'], $param['user_id']);
        if (!$res)self::apiReturn($client_id,'User Token Error!',0);
        return true;
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

    /**
     * （用户上线检测用户是否有离线消息）离线消息推送
     * @param $client_id
     * @param $user_id
     * @return bool
     */
    public static function offlineMsgPush($client_id, $user_id)
    {
        $offline_msg_list = OfflineMsg::where('push_id',$user_id)->orwhere('push_id',$client_id)->get()->toArray();
        if ($offline_msg_list){
            foreach ($offline_msg_list as $value){
                Gateway::sendToClient($client_id,$value['message']);
                OfflineMsg::destroy($value['id']);
            }
        }
        return true;
    }
}
