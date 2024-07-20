<?php
namespace mccwss\implements;

use think\Event;
use think\Request;
use Swoole\WebSocket\Frame;
use mccwss\interfaces\HandlerInterface;
use mccwss\implements\Event as WsEvent;

class Handler implements HandlerInterface
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * "onOpen" listener.
     *
     * @param Request $request
     */
    public function onOpen( $request)
    {
        $this->event->trigger('swoole.websocket.Open', $request);
    }

    /**
     * "onMessage" listener.
     *
     * @param Frame $frame
     */
    public function onMessage(Frame $frame)
    {
        $this->event->trigger('swoole.websocket.Message', $frame);

        $this->event->trigger('swoole.websocket.Event', $this->decode($frame->data));
    }

    /**
     * "onClose" listener.
     */
    public function onClose($fd)
    {
        $this->event->trigger('swoole.websocket.Close', $fd);
    }

    protected function decode($payload)
    {
        $data = json_decode($payload, true);

        return new WsEvent($data['type'] ?? null, $data['data'] ?? null);
    }

    public function encodeMessage($message)
    {
        if ($message instanceof WsEvent) {
            return json_encode([
                'type' => $message->type,
                'data' => $message->data,
            ]);
        }
        return $message;
    }
}
