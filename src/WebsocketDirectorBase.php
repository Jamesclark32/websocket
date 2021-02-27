<?php

namespace JamesClark32\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

abstract class WebsocketDirectorBase implements MessageComponentInterface
{
    abstract public function onOpen(ConnectionInterface $conn);

    abstract public function onClose(ConnectionInterface $conn);

    abstract public function onError(ConnectionInterface $conn, \Exception $e);

    abstract public function onMessage(ConnectionInterface $conn, MessageInterface $msg);

    abstract public function sendToAll(string $message);

    abstract public function sendToUsers(array $users, string $message);

    abstract public function sendToUser($user, string $message);
}
