<?php
declare(strict_types=1);

namespace Ex1;

class Bet
{
    public int $id;
    public float $amount;
    public string $command;

    public function __construct(int $id, float $am, string $cm)
    {
        $this->id = $id;
        $this->amount = $am;
        $this->command = $cm;
    }

    public function isWon(Game $game) : bool
    {
        if($game->getId() == $this->id && $game->getResult() == $this->command)
        {
            return true;
        } else {
            return false;
        }
    }
}