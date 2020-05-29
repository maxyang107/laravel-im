<?php

namespace App\Http\Controllers\Chart;

use App\Exceptions\ApiExceptions;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\server\PushServ;
use App\Model\Friendship;
use App\Model\OfflineMsg;
use GatewayClient\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartApiController extends ApiController
{
    private $rules = [
        'addFriends' => [
            'user_id'        => 'required|max:32',
            'friend_user_id' => 'required|max:32',
        ],
    ];

    private $message = [
        'addFriends' => [
            'user_id.required'        => '用户ID不能为空',
            'user_id.max'             => '用户ID最多32字符',
            'friend_user_id.required' => '好友ID不能为空',
            'friend_user_id.max'      => '好友ID最多32字符',
        ],

    ];

    //添加好友
    public function addFriends(Request $request)
    {
        $param     = $request->all(['user_id', 'friend_user_id']);
        $validator = Validator::make($param, $this->rules['addFriends'], $this->message['addFriends']);
        if ($validator->fails()) throw new ApiExceptions($validator->errors()->first());
        $map = [
            [
                'user_id'        => $param['user_id'],
                'friend_user_id' => $param['friend_user_id'],
                'status'         => 0,
                'create_at'      => time()
            ], [
                'user_id'        => $param['friend_user_id'],
                'friend_user_id' => $param['user_id'],
                'status'         => 0,
                'create_at'      => time()
            ]
        ];
        $res = Friendship::insert($map);
        if ($res){
            PushServ::pushMsgForUserId($param['friend_user_id'],['user_id'=>$param['user_id'],'message'=>"请求加好友"]);
            return $this->apiReturn(1, '请求发送成功！');
        }else{
            throw new ApiExceptions('服务器异常');
        }

    }
}
