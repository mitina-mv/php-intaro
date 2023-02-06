<?php
declare(strict_types=1);

namespace Ex2;

require_once 'FieldWithInterval.php';

class IntField extends FieldWithInterval
{
    public function isValid() : string
    {
        return $this->value >= $this->n 
            && $this->value <= $this->m 
            && preg_match("/^[-]?[0-9]+$/", $this->value) == 1 
        ? "OK" : "FAIL";
    }
}