<?php
// это установка строгой типизации
declare(strict_types=1);

// это неймспейс - пространство имен - данного класса
// для красоты, порядка и чтобы не смешивалось
namespace Ex1;

/**
   * Game
   * Игра. Храним данные о коэффициентах и результате.
   *
   * @package    Ex1
   * @author     mitina_mv
*/
class Game
{
    /**
       * ID игры в системе
       *
       * @var int $id
    */
    private int $id;

    /**
       * Коэффициент на победу левой команды
       *
       * @var array $games массив игр
    */
    private float $cl; 
    
    /**
       * Коэффициент на победу правой команды
       *
       * @var float $cr
    */    
    private float $cr;

    /**
       * Коэффициент на ничью
       *
       * @var float $cd
    */ 
    private float $cd;

    /**
       * Результат  игры
       *
       * @var string $result
    */ 
    private string $result;

    /**
       *
       * Конструктор игрока
       *
       * @param int $id id игры
       * @param float $cl коэффициент (левая команда)
       * @param float $cr коэффициент (правая команда)
       * @param float $cd коэффициент (ничья)
       * @param string $res результат игры
    */
    public function __construct(int $id, float $cl, float $cr, float $cd, string $res)
    {
        $this->id = $id;
        $this->cl = $cl;
        $this->cr = $cr;
        $this->cd = $cd;
        $this->result = $res;
    }

    // геттеры, чтобы вернуть приватные атрибуты класса
    public function getId() : int
    {
        return $this->id;
    }

    public function getCoefficientL() : float
    {
        return $this->cl;
    }

    public function getCoefficientR() : float
    {
        return $this->cr;
    }

    public function getCoefficientD() : float
    {
        return $this->cd;
    }

    public function getResult() : string
    {
        return $this->result;
    }
}