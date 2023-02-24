<?php
declare(strict_types=1);

namespace Ex3;

require_once 'FieldWithInterval.php';
/**
 * StringField
 * Поле типа строка (S)
 * 
 * @package Ex3
 * @author mitina_mv
 */
class StringField extends FieldWithInterval
{
    /**
     * Проверка строки на длинну строки и соотвествия ее содержания из символов, чисел и спец символов
     */
    public function isValid() : string
    {
        return preg_match("/^[\w\s\W]{" . $this->n . "," .$this->m . "}$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}