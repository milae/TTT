<?php declare(strict_types = 1);
namespace TicTacToe;
require('EventTypes.php');

class Game
{
    private $players;
    private $board = [
        0 => '',
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
        8 => ''
    ];

    public function __construct(Player $p1, Player $p2, $board = [])
    {
        $this->players = [$p1, $p2];
        if (!empty($board)) {
            $this->board = $board;
        }
    }

    public function move(Player $player, int $cellId): void
    {
        global $eventTypes;
        $this->board[$cellId] = $player->getSign();
        $markData = ['cellId' => $cellId, 'player' => $player->toJSON()];
        $marked = json_encode(['action' => $eventTypes['out']['MARK'], 'data' => $markData]);
        $nextPlayer = $this->getNextPlayer($player);
        $nextPlayer->getConnection()->send($marked);

        if ($this->hasWon($player)) {
            $result = json_encode(['action' => $eventTypes['out']['GAME_OVER'], 'data' => ['player' => $player->toJSON()]]);
        } else if ($this->isDraw()) {
            $result = json_encode(['action' => $eventTypes['out']['GAME_OVER'], 'data' => '']);
        } else {
            $result = json_encode(['action' => $eventTypes['out']['SET_TURN'], 'data' => $nextPlayer->toJSON()]);
        }

        foreach ($this->players as $player) {
            $player->getConnection()->send($result);
        }
    }

    public function hasWon(Player $currentPlayer): bool
    {
        //TODO TEIL 2: Implementieren Sie die Logik für das Erkennen des Siegers
        return false;
    }

    private function isDraw(): bool
    {
        foreach ($this->board as $cell) {
            if ($cell == '') {
                return false;
            }
        }
        return true;
    }

    private function getNextPlayer(Player $currentPlayer): Player
    {
        return $this->players[($currentPlayer->getID() + 1) % 2];
    }
}