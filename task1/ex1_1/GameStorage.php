<?php
// это установка строгой типизации
declare(strict_types=1);

// это неймспейс - пространство имен - данного класса
// для красоты, порядка и чтобы не смешивалось
namespace Ex1;

/**
   * GameStorage
   * Хранилище игр. Используется в качестве замены возможностей БД
   *
   * @package    Ex1
   * @author     mitina_mv
*/
class GameStorage
{
    /**
       * Массив массив игр в хранилище
       *
       * @var array $games массив игр
    */
    public array $games = [];

    /**
       *
       * Конструктор GameStorage
       * 
    */
    public function __construct()
    {
        return $this;
    }

    /**
       *
       * Добавление игры в хранилище
       *
       * @param Game $g объект игры, котрую добавляем
       * @return $this
    */
    public function setGame(Game $g)
    {
        // для ускорения получения игры в качестве ее ключа в массиве используем id игры
        $this->games[$g->getId()] = $g;

        return $this;
    }

    /**
       *
       * Получение игры по ID
       *
       * @param int $id id искомой игры
       * @return Game
    */
    public function getGame(int $id) : Game
    {
        // если id <= 0, то выбрасиваем ошибку - id не подходит 
        if($id <= 0)
        {
            throw new \Exception("Невалидный ID игры");
        }

        // если в массиве есть элемент по этому ключу, то возвращаем игру
        if(!empty($this->games[$id]))
        {
            return $this->games[$id];
        } 
        // игру не нашли - бросаем ошибку - id валидный, но нет такого id
        else {
            throw new \Exception("Игры с таким ID не найдена");
        }
    }
}