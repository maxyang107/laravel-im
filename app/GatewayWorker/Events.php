<?php


namespace App\GatewayWorker;

use App\Http\Controllers\Dispense\DistributeController;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;

class Events
{
    public static function onWorkerStart($businessWorker)
    {
        echo "onWorkerStart\r\n";
    }
    public static function onConnect($client_id)
    {
        Gateway::sendToClient($client_id,'用户id'.$client_id);
        echo "onConnect\r\n";
    }
    public static function onWebSocketConnect($client_id, $data)
    {
        echo "onWebSocketConnect\r\n";
    }
    public static function onMessage($client_id, $message)
    {
        if ('ping'!= strtolower($message)){
            DistributeController::handOut($client_id,$message);
        }
    }
    public static function onClose($client_id)
    {
        Log::info('Workerman close connection' . $client_id);
        echo "onClose\r\n";
    }

}
