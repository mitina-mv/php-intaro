<?php
declare(strict_types=1);

namespace Ex1;

class GameStorage
{
    public array $games = [];

    public function __construct()
    {
        return $this;
    }

    public function setGame(Game $g)
    {
        $this->games[$g->getId()] = $g;

        return $this;
    }

    public function getGame(int $id) : Game
    {
        if($id <= 0)
        {
            throw new \Exception("Невалидный ID игры");
        }

        if(!empty($this->games[$id]))
        {
            return $this->games[$id];
        } else {
            throw new \Exception("Игры с таким ID не найдена");
        }
    }
}