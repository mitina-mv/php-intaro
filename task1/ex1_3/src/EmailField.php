<?php
// типизация
declare(strict_types=1);

// неймспейс
namespace Ex3;

// подключение класса-родителя
require_once ('Field.php');

/**
 * EmailField
 * Поле типа email (E)
 * 
 * @package Ex3
 * @author mitina_mv
 * 
 */
class EmailField extends Field
{
    // реализация абстрактоного метода
    public function isValid(): string
    {
        return preg_match("/^[^_\s\W][\w]{3,30}@{1}[a-zA-Z]{2,30}[.]{1}[a-z]{2,10}$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}