<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class GatewayWorkerServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Workerman server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        global $argv;
        $action = $this->argument('action');
        $argv[0] = 'artisan workman';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';   //必须是一个-，上面定义命令两个--，后台启动用两个--

        $this->start();
    }

    private function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://127.0.0.1:2346");
        $gateway->name                 = 'Gateway';
        $gateway->count                = 4;
        $gateway->lanIp                = '127.0.0.1';
        $gateway->startPort            = 2900;
        $gateway->pingInterval         = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData             = '{"type":"ping"}';
        $gateway->registerAddress      = '127.0.0.1:1236';
    }

    private function startBusinessWorker()
    {
        $worker = new BusinessWorker();
        $worker->name            = 'BusinessWorker';
        $worker->count           = 4;
        $worker->registerAddress = '127.0.0.1:1236';
        $worker->eventHandler    = \App\GatewayWorker\Events::class;
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }


}
