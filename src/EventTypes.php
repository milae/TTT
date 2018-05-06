<?php declare(strict_types = 1);

global $eventTypes;
$eventTypes = [
    'out' => [
        'GAME_OVER' => 's2cGameOver',
        'SET_TURN' => 's2cSetTurn',
        'JOIN_GAME' => 's2cJoinGame',
        'MARK' => 's2cMark',
        'ERROR' => 's2cError',
        'QUIT' => 's2cQuit'
    ],
    'in' => [
        'JOIN_GAME' => 'c2sJoinGame',
        'MARK' => 'c2sMark',
        'QUIT' => 'c2sQuit'
    ]
];
?>