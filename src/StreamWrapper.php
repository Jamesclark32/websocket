<?php

namespace JamesClark32\Websocket;

use http\Exception\InvalidArgumentException;

class StreamWrapper
{
    protected $streamSocket = null;
    protected ?int $port = null;

    /**
     * @param  int  $port
     *
     * @return StreamWrapper
     */
    public function setPort(int $port): StreamWrapper
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return null
     */
    public function getStreamSocket()
    {
        return $this->streamSocket;
    }

    /**
     * Initialize the class by opening the stream socket server
     */
    public function initialize()
    {
        $this->setDefaults();

        $this->streamSocket = stream_socket_server('tcp://127.0.0.1:'.$this->port);
        stream_set_blocking($this->streamSocket, false);
        $this->validateIsResource($this->streamSocket);
    }

    /**
     * @param $streamSocket
     */
    protected function validateIsResource($streamSocket): void
    {
        if (! is_resource($streamSocket)) {
            throw new InvalidArgumentException(
                sprintf(
                    '$streamSocket provided to StreamWrapper::setStreamSocket() must be a valid resource type. %s given.',
                    gettype($streamSocket)
                )
            );
        }
    }

    /**
     * Set port to default if a port has not been assigned.
     */
    protected function setDefaults()
    {
        if (empty($this->port)) {
            $this->port = 8830;
        }
    }
}
