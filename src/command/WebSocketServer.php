<?php
namespace mccwss\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use Swoole\WebSocket\Server;

class WebSocketServer extends Command
{
    protected function configure()
    {
        $this->setName('mccwss')
            ->setDescription('Start WebSocket server');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('<info>Starting WebSocket server...</info>');

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

        // 启动服务器
        $ws->start();
    }
}