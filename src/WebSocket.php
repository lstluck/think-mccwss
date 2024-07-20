<?php
namespace mccwss;

class WebSocket
{
    public $ws;

    public function __construct(\think\App $app)
    {
        $this->app   = $app;
//        $this->room  = $room;
//        $this->event = $event;
    }
    function setws($par_ws){
        $this->ws = $par_ws;
    }


}
