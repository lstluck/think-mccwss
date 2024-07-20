<?php

return [
    'http'       => [
        'enable'     => true,
        'host'       => '0.0.0.0',
        'port'       => 8080,
        'worker_num' => swoole_cpu_num(),
        'options'    => [],
    ],
    'websocket'  => [
        'enable'     => true,
        'host'       => '0.0.0.0',
        'port'       => 8080,
        'mode'       => SWOOLE_PROCESS,
        'sockType'   => SWOOLE_SOCK_TCP,// SWOOLE_SOCK_TCP | SWOOLE_SSL
        'options'    => [
            'worker_num'        => 2,//swoole_cpu_num(),//设置启动的Worker进程数
            'reactor_num'       => swoole_cpu_num(),
            'task_worker_num'   => 2,//swoole_cpu_num(),//swoole_cpu_num(),

            'daemonize' => false,//守护进程化
            'dispatch_mode' => 2, //固定模式，保证同一个连接发来的数据只会被同一个worker处理

            //心跳检测：每60秒遍历所有连接，强制关闭10分钟内没有向服务器发送任何数据的连接
            'heartbeat_check_interval' => 60,//秒
            'heartbeat_idle_time' => 600,//秒

            'debug_mode' => 1,
            //'log_file' => '/www/wwwroot/1.92.137.230_9988/niucloud/public/swoole/error.log',//我为了记录出错记录的log

            //下边这俩证书，宝塔可以直接申请，位置就统一在这里了
            'ssl_cert_file' => '/etc/letsencrypt/live/xxx/fullchain.pem', //ssl证书
            'ssl_key_file' => '/etc/letsencrypt/live/xxx/privkey.pem', //ssl证书key
        ],
        'handler'       => \mccwss\implements\Handler::class,
        'ping_interval' => 25000,
        'ping_timeout'  => 60000,

        'listen'        => [],
        'subscribe'     => [],
    ],
    'hot_update' => [
        'enable'  => env('APP_DEBUG', false),
        'name'    => ['*.php'],
        'include' => [app_path()],
        'exclude' => [],
    ]
];
