<?php

namespace mccwss\websocket;

use Swoole\WebSocket\Server as SwooleWebsocketServer;
use mccwss\interfaces\HandlerInterface;
use mccwss\implements\Handler;
use think\app;



class Server
{
    private $ws = null;
    protected $Handler = null;

    public function __construct()
    {
        //$this->app->make(Handler::class)
        $this->Handler = app()->make(Handler::class);
        //$this->app->config()

        //$this->Handler =  $app->make(Handler::class);


        $this->ws = new SwooleWebsocketServer("0.0.0.0", 8080);
//        ,SWOOLE_PROCESS,SWOOLE_SOCKE_TCP | SWOOLE_SSL
//        protected $host = 'xxx.xxx.xxx.xxx';//你的ip地址，或者域名
//        protected $port = 39133;//端口号，记得要在安全组开放！
//        protected $serverType = 'socket';
//        protected $sockType = SWOOLE_SOCK_TCP | SWOOLE_SSL; //SWOOLE_SSL标识开启ssl，小程序wss协议要用，开这个必须把下边的两个证书配置好
//        protected $option = [
//        'worker_num' => 4, //设置启动的Worker进程数
//        'daemonize' => true,//守护进程化
//        'max_request' => 10000,
//        'dispatch_mode' => 2, //固定模式，保证同一个连接发来的数据只会被同一个worker处理
//        'debug_mode' => 1,
//        'log_file' => '/www/wwwroot/xxxx/public/swoole/error.log',//我为了记录出错记录的log
//        //心跳检测：每60秒遍历所有连接，强制关闭10分钟内没有向服务器发送任何数据的连接
//        'heartbeat_check_interval' => 60,
//        'heartbeat_idle_time' => 600,
//        //下边这俩证书，宝塔可以直接申请，位置就统一在这里了
//        'ssl_cert_file' => '/etc/letsencrypt/live/xxx/fullchain.pem', //ssl证书
//        'ssl_key_file' => '/etc/letsencrypt/live/xxx/privkey.pem', //ssl证书key
//    ];

        $this->ws->on('Open', [$this, "onOpen"]);
        $this->ws->on('Message', [$this, "onMessage"]);
        $this->ws->on('Close', [$this, "onClose"]);
        $this->ws->on('Request', [$this, "onRequest"]);
        $this->ws->start();
    }
    public function onOpen(SwooleWebsocketServer $ws, $request)
    {
        echo "客户端：{$request->fd} 已经成功连接！\n";
        $ws->push($request->fd, "欢迎客户端： {$request->fd}\n");
        $this->Handler->onOpen($request);
        //$this->event->trigger('swoole.websocket.Open', $request);
    }

    public function onMessage(SwooleWebsocketServer $ws, $frame)
    {
        echo "客户端 {$frame->fd}\n消息:{$frame->data}\nopcode:{$frame->opcode}\nfin:{$frame->finish}\n";
        $ws->push($frame->fd, "这里是服务器？");
        $this->Handler->onMessage($frame);
        //$this->event->trigger('swoole.websocket.Message', $frame);

    }

    public function onClose(SwooleWebsocketServer $ws, $fd)
    {
        echo "客户端： {$fd} 关闭\n";
        //$this->event->trigger('swoole.websocket.Close', $fd);
    }

    public function onRequest($request, $response)
    {
        // 接收http请求从get获取message参数的值，给用户推送
        // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
        foreach ($this->ws->connections as $fd) {
            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
            if ($this->ws->isEstablished($fd)) {
                //$this->ws->push($fd, $request->get['message']);
            }
        }
    }
}