<?php

namespace JamesClark32\Websocket;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class WebsocketServer
{
    protected ?HttpServer $httpServer = null;
    protected ?IoServer $ioServer = null;
    protected ?WebsocketDirectorBase $websocketDirector = null;
    protected ?WsServer $wsServer = null;
    protected ?StreamWrapper $streamWrapper = null;

    protected int $websocketPort = 8833;
    protected int $dataSocketPort = 8830;

    public function start()
    {
        $this->setDefaults();
        $this->startSocketServer();
        $this->startSocketListener();
        $this->run();
    }

    /**
     * @param  HttpServer  $httpServer
     *
     * @return WebsocketServer
     */
    public function setHttpServer(HttpServer $httpServer): WebsocketServer
    {
        $this->httpServer = $httpServer;

        return $this;
    }

    /**
     * @param  IoServer  $ioServer
     *
     * @return WebsocketServer
     */
    public function setIoServer(IoServer $ioServer): WebsocketServer
    {
        $this->ioServer = $ioServer;

        return $this;
    }

    /**
     * @param  WebsocketDirectorBase  $websocketDirector
     *
     * @return WebsocketServer
     */
    public function setWebsocketDirector(WebsocketDirectorBase $websocketDirector): WebsocketServer
    {
        $this->websocketDirector = $websocketDirector;

        return $this;
    }

    /**
     * @param  WsServer  $wsServer
     *
     * @return WebsocketServer
     */
    public function setWsServer(WsServer $wsServer): WebsocketServer
    {
        $this->wsServer = $wsServer;

        return $this;
    }

    /**
     *
     */
    protected function setDefaults(): void
    {
        if (empty($this->websocketDirector)) {
            $this->websocketDirector = new DefaultWebsocketDirector();
        }

        if (empty($this->wsServer)) {
            $this->wsServer = new WsServer($this->websocketDirector);
        }

        if (empty($this->httpServer)) {
            $this->httpServer = new HttpServer($this->wsServer);
        }

        if (empty($this->ioServer)) {
            $this->ioServer = IoServer::factory($this->httpServer, $this->websocketPort);
        }

        if (empty($this->streamWrapper)) {
            $this->streamWrapper = new StreamWrapper();
        }
    }

    /**
     *
     */
    protected function startSocketServer(): void
    {
        $this->streamWrapper->setPort($this->dataSocketPort)->initialize();
    }

    /**
     *
     */
    protected function startSocketListener(): void
    {
        $loop = $this->ioServer->loop;

        if ($loop) {
            try {
                $loop->addReadStream($this->streamWrapper->getStreamSocket(), function ($server) {
                    $conn = stream_socket_accept($server);
                    $message = fgets($conn);
                    $this->websocketDirector->sendToAll($message);
                });
            } catch (\Exception $e) {
                //@TODO: handle exception sanely
            }
        }
    }

    /**
     * @return HttpServer|null
     */
    public function getHttpServer(): ?HttpServer
    {
        return $this->httpServer;
    }

    /**
     * @return IoServer|null
     */
    public function getIoServer(): ?IoServer
    {
        return $this->ioServer;
    }

    /**
     * @return WebsocketDirectorBase|null
     */
    public function getWebsocketDirector(): ?WebsocketDirectorBase
    {
        return $this->websocketDirector;
    }

    /**
     * @return WsServer|null
     */
    public function getWsServer(): ?WsServer
    {
        return $this->wsServer;
    }

    /**
     * @return StreamWrapper|null
     */
    public function getStreamWrapper(): ?StreamWrapper
    {
        return $this->streamWrapper;
    }

    /**
     *
     */
    protected function run(): void
    {
        $this->ioServer->run();
    }

    /**
     * @param  StreamWrapper|null  $streamWrapper
     *
     * @return WebsocketServer
     */
    public function setStreamWrapper(?StreamWrapper $streamWrapper): WebsocketServer
    {
        $this->streamWrapper = $streamWrapper;

        return $this;
    }
}
