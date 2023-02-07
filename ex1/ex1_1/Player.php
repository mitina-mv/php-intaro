<?php
// это установка строгой типизации
declare(strict_types=1);

// это неймспейс - пространство имен - данного класса
// для красоты, порядка и чтобы не смешивалось
namespace Ex1;

/**
   * Player
   * Игрок. Сохраняем имя, ставки, получаем баланс
   *
   * @package    Ex1
   * @author     mitina_mv
*/
class Player
{
    /**
       * Массив ставок игрока
       *
       * @var array $bets объект ставки
    */
    private array $bets = [];

    /**
       * Итоговый баланс игрока. По умолчанию = 0
       * Пересчет по методу getBalance()
       *
       * @var float $balance итоговый баланс
    */
    private float $balance = 0;

    /**
       * Имя игрока. Для отладки.
       *
       * @var string $name имя игрока
    */
    public string $name;

    /**
       *
       * Конструктор игрока
       *
       * @param array $bets массив ставок
       * @param string $name имя игрока
    */
    public function __construct(array $bets, string $name)
    {
        $this->bets = $bets;
        $this->name = $name;
    }

    /**
       *
       * Добавление ставки для данного игрока
       *
       * @param Bet $bet объект ставки
       * @return $this
    */
    public function setBet(Bet $bet) 
    {
        $this->bets[] = $bet;

        return $this;
    }

    /**
       *
       * Получение баланса на основании ставок данного игрока
       *
       * @param GameStorage $gameStorage ссылка на хранилище игр
       * @return float итоговый баланс игрока (изменение баланса от суммы ставкок)
    */

    public function getBalance(GameStorage &$gameStorage) : float
    {
        // перебор ставок игрока
        foreach($this->bets as $bet)
        {
            try
            {
                // определяем, существует ли игра с такми ID (который указан в ставке)
                // если нет, поймаем ошибку
                $game = $gameStorage->getGame($bet->id);

                // условие: ставка сыграла? если да - высчитываем выигрыш
                if($bet->isWon($game))
                {
                    $coefficient = 0;

                    // определяем, какой коэффициент использовать для расчета
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

                        // если вариант результата игры не совпадает с ожидаемыми вариантами, бросаем ошибку
                        default:
                            throw new \Exception("Невалидный результат игры");
                            break;
                    }

                    // расчет чистого выиграла от размера ставки
                    $this->balance += $bet->amount * $coefficient - $bet->amount;
                } 
                // ставка не сыграла, отнимаем от баланса размер ставки
                else { 
                    $this->balance -= $bet->amount;
                }
            }
            // обработка ошибок - просто вывод сообщения
            catch (\Exception $e)
            {
                echo "Ошибка: {$e->getMessage()}";
            }
        }

        // возвращаем баланс
        return $this->balance;
    }
}