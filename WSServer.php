<?php declare(strict_types = 1);
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use TicTacToe\TTTServer;

$wsPort = 6502;
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new TTTServer()
        )
    ),
    $wsPort
);
$server->run();