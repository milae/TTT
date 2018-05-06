<?php

namespace TicTacToe\Tests;

use PHPUnit\Framework\TestCase;
use TicTacToe\Game;
use TicTacToe\Player;

class GameTest extends TestCase
{
    public function testWithEmptyBoardNoWinner()
    {
        $playerX = new Player(1, "Peter", "X");
        $playerY = new Player(2, "Paul", "Y");
        $board = [];
        $game = new Game($playerX, $playerY, $board);

        $this->assertEquals(false, $game->hasWon($playerX));
    }

    //TODO TEIL 2: Entwickeln Sie hier weitere Tests um die hasWon
    //Methode der Klasse Game zu testen
}
?>