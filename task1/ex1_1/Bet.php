<?php
// это установка строгой типизации
declare(strict_types=1);

// это неймспейс - пространство имен - данного класса
// для красоты, порядка и чтобы не смешивалось
namespace Ex1;

/**
    * Ставка на игру
    * 
    * @package    Ex1
    * @author     mitina_mv
 */
class Bet
{
    /**
       * ID игры, на которую сделана ставка
       *
       * @var int $id
    */
    public int $id;

    /**
       * Размер ставки
       *
       * @var float $amount
    */
    public float $amount;

    /**
       * На что ставили. Ожидаемый результат.
       *
       * @var string $command
    */
    public string $command;

    /**
       *
       * Конструктор Bet
       * 
    */
    public function __construct(int $id, float $am, string $cm)
    {
        $this->id = $id;
        $this->amount = $am;
        $this->command = $cm;
    }

    /**
       *
       * Проверка, сыграла ли ставка
       *
       * @param Game $game игра, на котрую была сделала ставка
       * @return bool
    */
    public function isWon(Game $game) : bool
    {
        // проверяем, совпадает ли id и сошелся ли результат
        // если да - true, ну а на нет и суда нет
        if($game->getId() == $this->id && $game->getResult() == $this->command)
        {
            return true;
        } else {
            return false;
        }
    }
}