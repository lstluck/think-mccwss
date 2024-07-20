<?php
namespace mccwss\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use mccwss\websocket\WebSocketServer;

class Server extends Command
{

    protected function configure()
    {

        $this->setName('mccwss')
            ->setDescription('Start WebSocket server');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->checkEnvironment();
        $output->writeln('<info>Starting mccwss WebSocket server...</info>');
        $output->writeln('You can exit with <info>`CTRL-C`</info>');

        new WebSocketServer();
    }
    protected function checkEnvironment()
    {
        if (!extension_loaded('swoole')) {
            $this->output->error('Can\'t detect Swoole extension installed.');
            exit(1);
        }

        if (!version_compare(swoole_version(), '4.6.0', 'ge')) {
            $this->output->error('Your Swoole version must be higher than `4.6.0`.');
            exit(1);
        }
    }
}