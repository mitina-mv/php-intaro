<?php
declare(strict_types=1);

namespace Ex3;

require_once 'FieldWithInterval.php';

/**
 * IntField
 * Поле типа целое число (N)
 * 
 * @package Ex3
 * @author mitina_mv
 */
class IntField extends FieldWithInterval
{
    public function isValid() : string
    {
        return $this->value >= $this->n 
            && $this->value <= $this->m 
            && preg_match("/^[-]?[0-9]+$/", $this->value) == 1 // проверка на число, чтобы исключить приведение типов символ к числу
        ? "OK" : "FAIL";
    }
}