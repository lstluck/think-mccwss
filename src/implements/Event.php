<?php

namespace mccwss\implements;

class Event
{
    public $type;
    public $data;

    public function __construct($type, $data = null)
    {
        $this->type = $type;
        $this->data = $data;
    }
}
