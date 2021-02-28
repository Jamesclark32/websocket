<?php

namespace JamesClark32\Websocket;

class WebsocketMessenger
{
    protected int $dataSocketPort = 8830;

    public function sendToAll(string $message)
    {
        $streamPointer = stream_socket_client('tcp://127.0.0.1:'.$this->dataSocketPort, $errorNumber, $errorMessage);
        if (!$streamPointer) {
            throw new \UnexpectedValueException (
                sprintf(
                    'stream_socket_client() failed with error %s: %s',
                    $errorNumber,
                    $errorMessage
                )
            );
        }
        fwrite($streamPointer, $message.PHP_EOL);
    }

    /**
     * @param  int  $dataSocketPort
     *
     * @return WebsocketMessenger
     */
    public function setDataSocketPort(int $dataSocketPort): WebsocketMessenger
    {
        $this->dataSocketPort = $dataSocketPort;
        return $this;
    }
}
