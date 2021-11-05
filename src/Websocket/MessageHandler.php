<?php
namespace App\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class MessageHandler implements MessageComponentInterface
{
    /**
     * @var SplObjectStorage
     */
    protected SplObjectStorage $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
        dump('Connection !');
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        dump('Message !');
        dump($msg);

        foreach ($this->connections as $connection) {
            $connection->send($msg);
            /*if ($connection !== $from) {
                dump('Send message');
                $connection->send($msg);
            }*/
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
        /* @todo manage error */
    }
}