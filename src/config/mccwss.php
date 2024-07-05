<?php

return [
    'websocket' => [
        'enable'     => true,
        'host'       => '0.0.0.0',
        'port'       => 8080,
        'worker_num' => swoole_cpu_num(),
        'options'    => [],
        'handler' => \think\swoole\websocket\Handler::class,
        'ping_interval' => 25000,
        'ping_timeout' => 60000,
        'room' => [
            'type' => 'table',
            'table' => [
                'room_rows' => 8192,
                'room_size' => 2048,
                'client_rows' => 4096,
                'client_size' => 2048,
            ],
            'redis' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'max_active' => 3,
                'max_wait_time' => 5,
            ],
        ],
        'listen' => [],
        'subscribe' => [],
    ],


];
