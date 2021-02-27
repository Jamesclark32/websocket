<?php

namespace JamesClark32\Websocket\Tests\DefaultWebsocketDirector;

use JamesClark32\Websocket\DefaultWebsocketDirector;
use PHPUnit\Framework\TestCase;
use Ratchet\ConnectionInterface;

class OnCloseTest extends TestCase
{
    /** @test */
    public function onClose_removes_user_from_clients_array()
    {
        $connection = new OnCloseConnection();
        $director = new DefaultWebsocketDirector();

        $director->onOpen($connection);

        $this->assertArrayHasKey($connection->resourceId, $director->getClients());

        $director->onClose($connection);

        $this->assertArrayNotHasKey($connection->resourceId, $director->getClients());

    }
}

class OnCloseConnection implements ConnectionInterface
{
    public int $resourceId;

    function __construct()
    {
        $this->resourceId = rand(1, 9999);
    }

    function send($data)
    {
        //
    }

    function close()
    {
        //
    }
}


