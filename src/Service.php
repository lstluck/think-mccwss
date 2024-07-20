<?php

namespace mccwss;

use mccwss\command\Server as ServerCommand;

class Service extends \think\Service
{
    public function boot()
    {

        $this->commands(
            ServerCommand::class
        );
    }
}

