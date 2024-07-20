<?php

namespace mccwss\interfaces;

use Swoole\WebSocket\Frame;
use think\Request;

interface HandlerInterface
{
    /**
     * "onOpen" listener.
     *
     * @param Request $request
     */
    public function onOpen( $request);

    /**
     * "onMessage" listener.
     *
     * @param Frame $frame
     */
    public function onMessage(Frame $frame);

    /**
     * "onClose" listener.
     */
    public function onClose($fd);

    public function encodeMessage($message);

}
