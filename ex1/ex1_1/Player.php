<?php
declare(strict_types=1);

namespace Ex1;

class Player
{
    private array $bets = [];
    private float $balance = 0;

    public function __construct(array $bets, array $games)
    {
        $this->bets = $bets;

        return $this;
    }

    public function setBet(Bet $bet) 
    {
        $this->bets[] = $bet;

        return $this;
    }

    public function getBalance(GameStorage &$gameStorage) : float
    {
        foreach($this->bets as $bet)
        {
            try
            {
                $game = $gameStorage->getGame($bet->id);

                if($bet->isWon($game))
                {
                    switch($game->getResult())
                    {
                        case 'R':
                            $this->balance += $bet->amount * $game->getCoefficientR();
                            break;

                        case 'L':
                            $this->balance += $bet->amount * $game->getCoefficientL();
                            break;

                        case 'D':
                            $this->balance += $bet->amount * $game->getCoefficientD();
                            break;

                        default:
                            throw new \Exception("Невалидный результат игры");
                    }
                } else {
                    $this->balance -= $bet->amount;
                }
            }
            catch (\Exception $e)
            {
                echo "Ошибка: {$e->getMessage()}";
            }
        }

        return $this->balance;
    }
}