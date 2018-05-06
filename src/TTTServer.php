<?php declare(strict_types = 1);
namespace TicTacToe;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require('EventTypes.php');

class TTTServer implements MessageComponentInterface
{
    private $clients;
    private $players = [];
    private $game;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $jsonObj = json_decode($msg, true);
        if ($jsonObj === null && json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON data is incorrect";
            return;
        }
        echo "Received message from connection! ({$from->resourceId})\n";

        global $eventTypes;
        switch ($jsonObj['action']) {
            case $eventTypes['in']['JOIN_GAME']:
                $this->handleJoinGame($from, $jsonObj, $eventTypes);
                break;
            case $eventTypes['in']['MARK']:
                $this->handleMark($jsonObj);
                break;
            case $eventTypes['in']['QUIT']:
                $this->handleQuit($eventTypes);
                break;
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
        unset($this->players[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendMessageToAll($action, $data): void
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['action' => $action, 'data' => $data]));
        }
    }

    private function getStartingSign(): string
    {
        return count($this->players) == 0 ? 'X' : 'O';
    }

    private function handleJoinGame(ConnectionInterface $from, $jsonObj, $eventTypes): void
    {
        if (count($this->players) < 2) {
            $id = count($this->players);
            $playerName = $jsonObj['data']['playerName'];
            $sign = $this->getStartingSign();
            $this->players[$from->resourceId] = new Player($id, $playerName, $sign, $from);

            foreach ($this->clients as $c) {
                foreach ($this->players as $resourceId => $p) {
                    $c->send(json_encode(['action' => $eventTypes['out']['JOIN_GAME'], 'data' => $p->toJSON()]));
                }
            }

            if (count($this->players) == 2) {
                $p1 = array_values($this->players)[0];
                $p2 = array_values($this->players)[1];
                $this->game = new Game($p1, $p2);
                $this->sendMessageToAll($eventTypes['out']['SET_TURN'], $p1->toJSON());
            }
        }
    }

    private function handleMark($jsonObj): void
    {
        $player = array_values($this->players)[$jsonObj['data']['playerId']];
        $cellId = $jsonObj['data']['cellId'];
        $this->game->move($player, $cellId);
    }

    private function handleQuit($eventTypes): void
    {
        $this->sendMessageToAll($eventTypes['out']['QUIT'], []);
    }
}