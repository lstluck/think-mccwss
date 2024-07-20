<?php

namespace mccwss\websocket;

use Swoole\WebSocket\Server as SwooleWebsocketServer;
use mccwss\interfaces\HandlerInterface;
use mccwss\WebSocket;

class WebSocketServer
{
    private $ws = null;
    private $handler = null;

    public function __construct()
    {
        $host = config('mccwss.websocket.host','0.0.0.0');
        $port = config('mccwss.websocket.port',8080);
        $mode = config('mccwss.websocket.mode',SWOOLE_PROCESS);
        $sockType = config('mccwss.websocket.sockType',SWOOLE_SOCK_TCP) ;
        $options = config('mccwss.websocket.options',[]);
        $this->bindWebsocketHandler();

        $this->ws = new SwooleWebsocketServer($host, $port,$mode,$sockType);
        $this->ws->set($options);

        $wss = app(Websocket::class, [], true);
        app()->instance(Websocket::class, $wss);
        $wss->setws($this->ws);

        $this->handler = app(HandlerInterface::class);

        $this->ws->on('Open', [$this, "onOpen"]);
        $this->ws->on('Message', [$this, "onMessage"]);
        $this->ws->on('Close', [$this, "onClose"]);
        $this->ws->on('Request', [$this, "onRequest"]);

        $this->ws->on('WorkerStart', [$this, "onWorkerStart"]);
        $this->ws->on('Task', [$this, "onTask"]);
        $this->ws->start();
    }

    public function onTask($ws,  $task){

    }

    public function onWorkerStart($ws, $workerId)
    {
        // 只在非Task进程上注册信号处理
    }

    public function onOpen($ws, $request)
    {
//        echo "客户端：{$request->fd} 已经成功连接！\n";
//        $ws->push($request->fd, "欢迎客户端： {$request->fd}\n");
        $this->handler->onOpen($request);
    }

    public function onMessage($ws, $frame)
    {
//        echo "客户端 {$frame->fd}\n消息:{$frame->data}\nopcode:{$frame->opcode}\nfin:{$frame->finish}\n";
//        $ws->push($frame->fd, "这里是服务器？");
        $this->handler->onMessage($frame);
    }

    public function onClose($ws, $fd)
    {
//        echo "客户端： {$fd} 关闭\n";
        $this->handler->onClose($fd);
    }

    public function onRequest($request, $response)
    {
//         接收http请求从get获取message参数的值，给用户推送
//         $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
        foreach ($this->ws->connections as $fd) {
            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
            if ($this->ws->isEstablished($fd)) {
                //$this->ws->push($fd, $request->get['message']);
            }
        }
    }

    protected function bindWebsocketHandler()
    {
        $handlerClass = config('mccwss.websocket.handler');
        bind(HandlerInterface::class, $handlerClass);
    }

}