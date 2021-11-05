<?php
namespace App\Command;

use App\Websocket\MessageHandler;
use Exception;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WebsocketServerCommand extends Command
{
    protected static $defaultName = 'app:websocket:server';
    protected static $defaultDescription = 'Websocket server';

    public function __construct(
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln("Starting server on 127.0.0.1:9001");

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new MessageHandler()
                )
            ),
            9001
        );

        $server->run();

        $io->success('WebsocketServerCommand stopped');

        return Command::SUCCESS;
    }
}
