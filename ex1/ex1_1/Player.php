<?php
declare(strict_types=1);

namespace Ex1;

class Player
{
    private array $bets = [];
    private float $balance = 0;
    public string $name;

    public function __construct(array $bets, string $name)
    {
        $this->bets = $bets;
        $this->name = $name;

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
                    $coefficient = 0;

                    switch($game->getResult())
                    {
                        case 'R':
                            $coefficient = $game->getCoefficientR();
                            break;

                        case 'L':
                            $coefficient = $game->getCoefficientL();
                            break;

                        case 'D':
                            $coefficient = $game->getCoefficientD();
                            break;

                        default:
                            throw new \Exception("Невалидный результат игры");
                            break;
                    }

                    $this->balance += $bet->amount * $coefficient - $bet->amount;
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