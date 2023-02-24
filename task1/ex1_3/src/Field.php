<?php
// типизация
declare(strict_types=1);

// неймспейс
namespace Ex3;

/**
 * Field
 * Абстрактный класс поля, реализующий основной функционал
 * 
 * @package    Ex3
 * @author     mitina_mv
 */
abstract class Field
{
    /**
       * Значение внутри поля
       *
       * @var string $value
    */
    protected string $value;

    /**
       * Тип поля (определяющий правила валидации)
       *
       * @var string $type
    */
    protected string $type;

    /**
     * Конструктор Field
     */
    public function __construct(string $v, string $t)
    {
        $this->type = $t;
        $this->value = $v;
    }

    // геттеры класса
    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Field
     * Абстрактный метод проверки значения на валидность
     * 
     * @return string
     */
    abstract public function isValid() : string;
}