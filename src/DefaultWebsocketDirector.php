<?php

namespace JamesClark32\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * A default, simplistic websocket director.
 *
 * Class DefaultWebsocketDirector
 *
 * @package JamesClark32\Websocket
 */
class DefaultWebsocketDirector extends WebsocketDirectorBase
{
    protected array $clients;

    /**
     * A new websocket client has opened a connection
     *
     * @param  ConnectionInterface  $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$this->fetchResourceIdFromConnection($conn)] = $conn;
    }

    /**
     * The specified ConnectionInterface has closed the websocket connection
     *
     * @param  ConnectionInterface  $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clients[$this->fetchResourceIdFromConnection($conn)]);
    }

    /**
     * An error was encountered
     *
     * @param  ConnectionInterface  $conn
     * @param  \Exception  $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo 'Error Encountered: '.$e->getMessage().PHP_EOL;
        $conn->close();
    }

    /**
     * Handles a new message coming from the javascript end
     * of the websocket
     *
     * @param  ConnectionInterface  $conn
     * @param  MessageInterface  $msg
     */
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        $this->sendToAll((string) $msg->getPayload());
    }

    /**
     * Sends a message to all connected users via the websocket
     *
     * @param  string  $message
     */
    public function sendToAll(string $message)
    {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }

    /**
     * Sends a message to a group of users via the websocket
     *
     * @param  array  $users
     * @param  string  $message
     */
    public function sendToUsers(array $users, string $message)
    {
        foreach ($users as $user) {
            $this->sendToUser($user, $message);
        }
    }

    /**
     * Sends a message to a specific user via the websocket
     *
     * The user is identified by their resourceId,
     * which isn't all that viable.
     *
     * More likely something should be done to identify the user during
     * onOpen(), such as sending data from the client to identify them,
     * and the array of clients modified to use a more user-identifying key.
     *
     * @param $user
     * @param  string  $message
     */
    public function sendToUser($user, string $message)
    {
        $this->clients[$user]->send($message);
    }

    /**
     * Returns the resource id from the connection, if it exists
     * Returns null otherwise
     *
     * @param  ConnectionInterface  $connection
     *
     * @return string|int
     */
    protected function fetchResourceIdFromConnection(ConnectionInterface $connection)
    {
        if (property_exists($connection, 'resourceId')) {
            return $connection->resourceId;
        }

        return count($this->clients);
    }

    /**
     * @return array
     */
    public function getClients(): array
    {
        return $this->clients;
    }
}
