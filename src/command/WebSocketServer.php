<?php
namespace mccwss\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use mccwss\websocket\Server;

class WebSocketServer extends Command
{
    public $event = null;
    protected function configure()
    {

        $this->setName('mccwss')
            ->setDescription('Start WebSocket server');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('<info>Starting WebSocket server...</info>');

        new Server();
    }
}