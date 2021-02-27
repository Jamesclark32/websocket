<?php

namespace JamesClark32\Websocket\Tests\WebsocketServer;


use JamesClark32\Websocket\StreamWrapper;
use JamesClark32\Websocket\WebsocketDirectorBase;
use JamesClark32\Websocket\WebsocketServer;
use PHPUnit\Framework\TestCase;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class StartTest extends TestCase
{
    /** @test */
    public function start_method_sets_defaults()
    {
        $websocketServer = \Mockery::mock(WebsocketServer::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();


        $websocketServer->shouldReceive('run')->once();
        $websocketServer->shouldReceive('startSocketListener')->once();
        $websocketServer->shouldReceive('startSocketServer')->once();

        $this->assertNull($websocketServer->getHttpServer());
        $this->assertNull($websocketServer->getIoServer());
        $this->assertNull($websocketServer->getWebsocketDirector());
        $this->assertNull($websocketServer->getWsServer());
        $this->assertNull($websocketServer->getStreamWrapper());

        $websocketServer->start();

        $this->assertInstanceOf(HttpServer::class, $websocketServer->getHttpServer());
        $this->assertInstanceOf(IoServer::class, $websocketServer->getIoServer());
        $this->assertInstanceOf(WebsocketDirectorBase::class, $websocketServer->getWebsocketDirector());
        $this->assertInstanceOf(WsServer::class, $websocketServer->getWsServer());
        $this->assertInstanceOf(StreamWrapper::class, $websocketServer->getStreamWrapper());
    }

    /** @test */
    public function start_method_calls_setPort_and_initialize_methods_on_socketServer()
    {
        $streamWrapper = \Mockery::mock(StreamWrapper::class);

        $streamWrapper->shouldReceive('setPort')->once()->andReturnSelf();
        $streamWrapper->shouldReceive('initialize');

        $ioServer = \Mockery::mock(IoServer::class);

        $websocketServer = \Mockery::mock(WebsocketServer::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $websocketServer->shouldReceive('startSocketListener')->once();
        $websocketServer->shouldReceive('run')->once();


        $websocketServer->setStreamWrapper($streamWrapper);
        $websocketServer->setIoServer($ioServer);
        $websocketServer->start();

        $container = \Mockery::getContainer();
        $this->addToAssertionCount($container->mockery_getExpectationCount());
    }
}
