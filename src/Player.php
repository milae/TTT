<?php declare(strict_types = 1);

namespace TicTacToe;

use Ratchet\ConnectionInterface;

class Player
{
    private $connection;
    private $id;
    private $name;
    private $sign;

    public function __construct(int $id,
                                string $name,
                                string $sign,
                                ConnectionInterface $connection = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sign = $sign;
        $this->connection = $connection;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function toJSON(): array
    {
        return array('name' => $this->name, 'id' => $this->id, 'sign' => $this->sign);
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function getID(): int
    {
        return $this->id;
    }
}