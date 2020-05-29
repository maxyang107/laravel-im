<?php


namespace App\handlers;

class WorkermanHandler
{
    public function onWorkerStart($worker)
    {
        //加载index文件的内容
        require __DIR__ . '/../../vendor/autoload.php';
        require_once __DIR__ . '/../../bootstrap/app.php';
    }

    // 处理客户端连接
    public function onConnect($connection)
    {
        echo "new connection from ip " . $connection->getRemoteIp() . "\n";
    }

    // 处理客户端消息
    public function onMessage($connection, $data)
    {
        // 向客户端发送hello $data
        //server信息
        if (isset($data->server)) {
            foreach ($data->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        //header头信息
        if (isset($data->header)) {
            foreach ($data->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        //get请求
        if (isset($data->get)) {
            foreach ($data->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        //post请求
        if (isset($data->post)) {
            foreach ($data->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        //文件请求
        if (isset($data->files)) {
            foreach ($data->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }

        //cookies请求
        if (isset($data->cookie)) {
            foreach ($data->cookie as $k => $v) {
                $_COOKIE[$k] = $v;
            }
        }

        ob_start();//启用缓存区

        //加载laravel请求核心模块
        $kernel = app()->make(Illuminate\Contracts\Http\Kernel::class);
        $laravelResponse = $kernel->handle(
            $request = Illuminate\Http\Request::capture()
        );
        $laravelResponse->send();
        $kernel->terminate($request, $laravelResponse);

        $res = ob_get_contents();//获取缓存区的内容
        ob_end_clean();//清除缓存区

        //输出缓存区域的内容
        $connection->send($res);
    }
    // 处理客户端断开
    public function onClose($connection)
    {
        echo "connection closed from ip {$connection->getRemoteIp()}\n";
    }

}
