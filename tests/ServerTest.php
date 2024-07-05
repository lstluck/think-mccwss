<?php

namespace tests\mccwss;




use mccwss\Service;
use Swoole\WebSocket\Server;

class ServerTest
{
    public function __construct()
    {
        $ws = new Server("0.0.0.0", 8080);

        // 设置事件回调函数
        $ws->on('open', function (Server $ws, $request) {
            echo "connection open: {$request->fd}\n";
        });

        $ws->on('message', function (Server $ws, $frame) {
            echo "received message: {$frame->data}\n";
            $ws->push($frame->fd, json_encode(["hello", "world"]));
        });

        $ws->on('close', function (Server $ws, $fd) {
            echo "connection close: {$fd}\n";
        });
        $ws->start();
    }

}

new ServerTest();
