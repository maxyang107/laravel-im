<?php


namespace App\Http\Controllers\server;

use App\Model\OfflineMsg;
use GatewayClient\Gateway;

class PushServ
{
    public static function pushSysMsg($obj = ['type' => 'all', 'id' => ''], $type = 'sys', $code = 0, $info = 'error', $data = [])
    {
        Gateway::$registerAddress = '127.0.0.1:1236';
        $msg                      = json_encode([
            'type' => $type,
            'code' => 0,
            'info' => $info,
            'data' => $data
        ], true);
        //全平台推送
        if ($obj['type'] == 'all') {
            Gateway::sendToAll($msg);
        } elseif ($obj['type'] == 'group') {//群组消息推送
            Gateway::sendToGroup($obj['id'], $msg);
        } else {//点对点消息推送
            Gateway::sendToClient($obj['id'], $msg);
        }
    }


    public static function pushMsgForUserId($user_id, $message = '')
    {
        $message                  = json_encode($message);
        Gateway::$registerAddress = '127.0.0.1:1236';
        if (Gateway::isUidOnline($user_id)) {
            Gateway::sendToUid($user_id, $message);
            return true;
        } else {//不在线的时候将信息推入离线消息列表
            self::offlineMsg('user_id', $user_id, $message);
            return true;
        }
    }

    //离线消息缓存
    public static function offlineMsg($type = 'user_id', $push_id, $message)
    {
        $map = [
            'push_type'      => $type,
            'push_id'   => $push_id,
            'message'   => $message,
            'create_at' => time()
        ];
        OfflineMsg::create($map);
        return true;
    }
}
